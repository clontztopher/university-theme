/**
 * Vanilla JS Version
 */

 import axios from "axios"

 class Search {
   // 1. describe and create/initiate our object
   constructor() {
     this.addSearchHTML()
     this.resultsDiv = document.querySelector("#search-overlay__results")
     this.openButton = document.querySelectorAll(".js-search-trigger")
     this.closeButton = document.querySelector(".search-overlay__close")
     this.searchOverlay = document.querySelector(".search-overlay")
     this.searchField = document.querySelector("#search-term")
     this.isOverlayOpen = false
     this.isSpinnerVisible = false
     this.previousValue
     this.typingTimer
     this.events()
   }
 
   // 2. events
   events() {
     this.openButton.forEach(el => {
       el.addEventListener("click", e => {
         e.preventDefault()
         this.openOverlay()
       })
     })
 
     this.closeButton.addEventListener("click", () => this.closeOverlay())
     document.addEventListener("keydown", e => this.keyPressDispatcher(e))
     this.searchField.addEventListener("keyup", () => this.typingLogic())
   }
 
   // 3. methods (function, action...)
   typingLogic() {
     if (this.searchField.value != this.previousValue) {
       clearTimeout(this.typingTimer)
 
       if (this.searchField.value) {
         if (!this.isSpinnerVisible) {
           this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'
           this.isSpinnerVisible = true
         }
         this.typingTimer = setTimeout(this.getResults.bind(this), 750)
       } else {
         this.resultsDiv.innerHTML = ""
         this.isSpinnerVisible = false
       }
     }
 
     this.previousValue = this.searchField.value
   }
 
   async getResults() {
     try {
       const response = await axios.get(universityData.root_url + "/wp-json/university/v1/search?term=" + this.searchField.value)
       const results = response.data
       this.resultsDiv.innerHTML = `
         <div class="row">
           <div class="one-third">
             <h2 class="search-overlay__section-title">General Information</h2>
             ${results.generalInfo.length ? '<ul class="link-list min-list">' : "<p>No general information matches that search.</p>"}
               ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
             ${results.generalInfo.length ? "</ul>" : ""}
           </div>
           <div class="one-third">
             <h2 class="search-overlay__section-title">Programs</h2>
             ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`}
               ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
             ${results.programs.length ? "</ul>" : ""}
 
             <h2 class="search-overlay__section-title">Professors</h2>
             ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that search.</p>`}
               ${results.professors
           .map(
             item => `
                 <li class="professor-card__list-item">
                   <a class="professor-card" href="${item.permalink}">
                     <img class="professor-card__image" src="${item.image}">
                     <span class="professor-card__name">${item.title}</span>
                   </a>
                 </li>
               `
           )
           .join("")}
             ${results.professors.length ? "</ul>" : ""}
 
           </div>
           <div class="one-third">
             <h2 class="search-overlay__section-title">Campuses</h2>
             ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all campuses</a></p>`}
               ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
             ${results.campuses.length ? "</ul>" : ""}
 
             <h2 class="search-overlay__section-title">Events</h2>
             ${results.events.length ? "" : `<p>No events match that search. <a href="${universityData.root_url}/events">View all events</a></p>`}
               ${results.events
           .map(
             item => `
                 <div class="event-summary">
                   <a class="event-summary__date t-center" href="${item.permalink}">
                     <span class="event-summary__month">${item.month}</span>
                     <span class="event-summary__day">${item.day}</span>  
                   </a>
                   <div class="event-summary__content">
                     <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                     <p>${item.description} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                   </div>
                 </div>
               `
           )
           .join("")}
 
           </div>
         </div>
       `
       this.isSpinnerVisible = false
     } catch (e) {
       console.log(e)
     }
   }
 
   keyPressDispatcher(e) {
     if (e.keyCode == 83 && !this.isOverlayOpen && document.activeElement.tagName != "INPUT" && document.activeElement.tagName != "TEXTAREA") {
       this.openOverlay()
     }
 
     if (e.keyCode == 27 && this.isOverlayOpen) {
       this.closeOverlay()
     }
   }
 
   openOverlay() {
     this.searchOverlay.classList.add("search-overlay--active")
     document.body.classList.add("body-no-scroll")
     this.searchField.value = ""
     setTimeout(() => this.searchField.focus(), 301)
     console.log("our open method just ran!")
     this.isOverlayOpen = true
     return false
   }
 
   closeOverlay() {
     this.searchOverlay.classList.remove("search-overlay--active")
     document.body.classList.remove("body-no-scroll")
     console.log("our close method just ran!")
     this.isOverlayOpen = false
   }
 
   addSearchHTML() {
     document.body.insertAdjacentHTML(
       "beforeend",
       `
       <div class="search-overlay">
         <div class="search-overlay__top">
           <div class="container">
             <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
             <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
             <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
           </div>
         </div>
         
         <div class="container">
           <div id="search-overlay__results"></div>
         </div>
 
       </div>
     `
     )
   }
 }
 
 export default Search
 


/**
 * jQuery Version
 */

// import $ from 'jquery';

// class Search {
  
//   // 1. Initializer
//   constructor() {

//     // Add template immediately so the selectors 
//     // underneath will have something to grab
//     this.addSearchHTML();
    
//     // Cache jQuery objects for needed elements
//     this.openButton = $('.js-search-trigger');
//     this.closeButton = $('.search-overlay__close');
//     this.searchOverlay = $('.search-overlay');
//     this.searchField = $('#search-term');
//     this.resultsDiv = $('#search-overlay__results');
    
//     // Initialize event listeners
//     this.events();

//     // set module variables
//     this.isOverlayOpen = false;
//     this.isSpinnerVisible = false;
//     this.previousValue;
//     this.typingTimer;
//   }

//   // 2. Events
//   events() {
//     this.openButton.on('click', this.openOverlay.bind(this));
//     this.closeButton.on('click', this.closeOverlay.bind(this));
//     $(document).on('keydown', this.keyPressDispatcher.bind(this));
//     this.searchField.on('keyup', this.typingLogic.bind(this));
//   }

//   // 3. Methods
//   typingLogic() {
//     if (this.searchField.val() != this.previousValue) {
//       clearTimeout(this.typingTimer);

//       if (this.searchField.val() != '') {
//         if (!this.isSpinnerVisible) {
//           this.resultsDiv.html('<div class="spinner-loader"></div>');
//           this.isSpinnerVisible = true;
//         }
//         this.typingTimer = setTimeout(this.getResults.bind(this), 750);
//       } else {
//         this.resultsDiv.html('');
//         this.isSpinnerVisible = false;
//       }
//       this.previousValue = this.searchField.val();
//     }
//   }

//   openOverlay() {
//     this.searchOverlay.addClass('search-overlay--active');
//     $('body').addClass("body-no-scroll");
//     this.searchField.val('');
//     setTimeout(() => this.searchField.focus(), 301)
//     this.isOverlayOpen = true;
//   }
  
//   closeOverlay() {
//     this.searchOverlay.removeClass('search-overlay--active');
//     $('body').removeClass('body-no-scroll');
//     this.isOverlayOpen = false;
//   }

//   keyPressDispatcher(e) {
//     if (e.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
//       this.openOverlay();
//     }

//     if (e.keyCode == 27 && this.isOverlayOpen) {
//       this.closeOverlay();
//     }
//   }

//  getResults() {
//    /**
//     * universityData object is supplied by WordPress via functions.php
//     */
//     $.getJSON(universityData['root_url'] + '/wp-json/university/v1/search?term=' + this.searchField.val(), results => {
      
//       this.resultsDiv.html(`
//         <div class="row">
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">General Information</h2>
//             ${results.general.length > 0 
//               ? `<ul class="link-list min-list">
//                 ${results.general.map(item => `<li><a href="${item.permalink}" >${item.title}</a> ${item.type == 'post' ? `by ${item.author_name}` : ''}</li>`).join('')}
//                 </ul>` 
//               : `<p>No general information matches your search.</p>`}
//           </div>
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">Programs</h2>
//             ${results.programs.length > 0 
//               ? `<ul class="link-list min-list">
//                 ${results.programs.map(item => `<li><a href="${item.permalink}" >${item.title}</a></li>`).join('')}
//                 </ul>` 
//               : `<p>No programs match your search. <a href="${universityData['root_url']}/programs">View all programs.</a></p>`}
//             <h2 class="search-overlay__section-title">Professors</h2>
//             ${results.professors.length > 0 
//               ? `<ul class="professor-cards">
//                 ${results.professors.map(item => `
//                 <li class="professor-card__list-item">
//                   <a class="professor-card" href="${item.permalink}">
//                     <img src="${item.image}" alt="" class="professor-card__image">
//                     <span class="professor-card__name">${item.title}</span>
//                   </a>
//                 </li>
//                 `).join('')}
//                 </ul>` 
//               : `<p>No professors match your search.</p>`}
//           </div>
//           <div class="one-third">
//             <h2 class="search-overlay__section-title">Campuses</h2>
//             ${results.campuses.length > 0 
//               ? `<ul class="link-list min-list">
//                 ${results.campuses.map(item => `<li><a href="${item.permalink}" >${item.title}</a></li>`).join('')}
//                 </ul>` 
//               : `<p>No campuses match your search. <a href="${universityData['root_url']}/campuses">View all campuses.</a></p>`}
//             <h2 class="search-overlay__section-title">Events</h2>
//             ${results.events.length > 0 
//               ? results.events.map(item => `
//               <div class="event-summary">
//                 <a class="event-summary__date t-center" href="${item.permalink}">
//                   <span class="event-summary__month">${item.month}</span>
//                   <span class="event-summary__day">${item.day}</span>
//                 </a>
//                 <div class="event-summary__content">
//                   <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
//                   <p>${item.excerpt} <a href="${item.permalink}" class="nu gray">Learn more</a></p>
//                 </div>
//             </div>
//               `).join('')
//               : `<p>No events match your search. <a href="${universityData['root_url']}/events">View all events.</a></p>`}
//           </div>
//         </div>
//       `);

//       this.isSpinnerVisible = false;
//     }, err => {
//       this.resultsDiv.html('Unexpected error, please try again.');
//       console.warn(err);
//     });

//  }

//  // Append html template to 
//  // body for search overlay
//  addSearchHTML() {
//    $('body').append(`
//    <div class="search-overlay">
//      <div class="search-overlay__top">
//        <div class="container">
//          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
//          <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
//          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
//        </div>
//      </div>
//      <div class="container">
//        <div id="search-overlay__results">
   
//        </div>
//      </div>
//    </div>
//    `);
//  }
// }

// export default Search;