/**
 * Note: using window.____ global variables b/c this website
 * dynamically injects new JavaScript scripts into the DOM
 * Each time this script is run, it removes and re-initializes
 * the class and corresponding object.
 */

import showLightbox from './lightbox.js';

window.CourseBrowser = class {
    courses = { };

    constructor () {
        document.querySelector('#term').addEventListener('change', this.fetchAndDisplay.bind(this));
    }

    async fetchWordpressCourses () {
        const url = '/wp-json/wp/v2/courses?per_page=100';
        const response = await fetch(url);
        return await response.json();
    }

    async fetchCourses () {
        const baseScheduleUrl = 'https://meteor.unca.edu/registrar/class-schedules/api/v1/courses/';
        document.querySelector('#course-list').innerHTML = "Searching...";
        const termUrl = document.querySelector('#term').value;
        const url = baseScheduleUrl + termUrl;
        return await fetch(url).then(response => response.json());
    }

    async fetchAndDisplay () {
        this.coursesWordpress = await this.fetchWordpressCourses();
        console.log(this.coursesWordpress);
        this.courses = await this.fetchCourses();
        this.displayResults(this.courses);
    }

    getCourseCode(courseCode) {
        courseCode = courseCode.split('.')[0];
        console.log(courseCode);
        const results = this.coursesWordpress.filter(course => {
            return course.title.rendered.toUpperCase().includes(courseCode.toUpperCase())
        });
        console.log(results);
        if (results.length === 1) {
            return results[0].id;
        }
        return null;
    }


    async displayResults(courseList) {
        console.log('displaying results!');
        document.querySelector('#course-list').innerHTML = "";
        // access the #results section and put the course title into it. 
        let termUrl;
        try {
            termUrl = document.querySelector('#term').value;
        } catch(e) {
            console.log('slide changed');
            return;
        }
                
        for (let i = 0; i < courseList.length; i++) {
            const course = courseList[i];
            if (course.Department == "CSCI") {
                this.displayCourse(course);
            }
        }
        // this.toJSON();
    }

    addToInventory(courseData) {
        const code = courseData.Code.split(".")[0];
        this.courses[code] = {
            code: code,
            title: courseData.Title,
            description: courseData.Description,
            credit_hours: courseData.Hours,
            level: courseData.Code[5] + "00"
        }
    }

    toJSON() {
        console.log(JSON.stringify(this.courses));
    }

    getMoreInfo(code) {
        const postId = this.getCourseCode(code);
        if (postId) {
            return `<button class="link" onclick="showCourse(${postId})">More Info</a>`;
        }
        return '';
    }

    displayCourse(course) {
        // console.log(course);
        // don't access the first instructor if no instructors are present:
        let instructor = 'Unknown';
        if (course.Instructors.length > 0) {
            instructor = course.Instructors[0].Name;
        }
        const spaceLeft = Math.max(course.EnrollmentMax - course.EnrollmentCurrent, 0);
        const closed = spaceLeft === 0 ? true : false;
        const numOnWaitlist = course.WaitlistMax - course.WaitlistAvailable;
        const startTime = new Date(course.StartTime).toLocaleTimeString([], {timeStyle: 'short'});
        const endTime = new Date(course.EndTime).toLocaleTimeString([], {timeStyle: 'short'});
        const meets = course.Days ? course.Days : "";
        const location = course.Location.FullLocation ? course.Location.FullLocation + " &bull;" : "";
        const template = `
            <section class="course">
                <h3>${course.Code}: ${course.Title}</h3>
                <p>
                    ${closed ? '<i class="fa-solid fa-circle-xmark"></i> Closed' : '<i class="fa-solid fa-circle-check"></i> Open'} 
                    &bull; ${course.CRN}
                    &bull;  
                    ${!closed ? "Seats Available: " + spaceLeft : "Number on Waitlist " + numOnWaitlist}
                    &bull;
                    <strong>${meets} ${startTime} - ${endTime}</strong> &bull; 
                    ${ location }
                    ${course.Hours} credit hour(s)
                </p>
                <p><strong>${instructor}</strong></p>
                ${ this.getMoreInfo(course.Code) }
                <!-- <p>${course.Description}</p> -->
            </section>`;
        try {
            document.querySelector('#course-list').insertAdjacentHTML('beforeend', template);
        } catch(e) {
            console.log(e);
        }
    }

}

window.browser = new CourseBrowser();
browser.fetchAndDisplay();