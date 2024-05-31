// Constants
const RICE_CROP_MONTHS = [4, 5, 6, 7, 8, 9, 10];
const CORN_CROP_MONTHS = [11, 0, 1, 2];
const months = ["January", "February",  "March", "April", "May", "June", "July",  "August", "September", "October", "November", "December"];
const date = new Date();
  // Variables
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let statusData = {};

// DOM Elements
const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");
  recommendedCropIcon = document.getElementById("recommended-crop-icon1"); // Get the recommended crop icon element

const days = document.querySelectorAll(".days .day");

// Functions
function renderCalendar() {
  fetch(`fetch_status.php?month=${currentMonth + 1}&year=${currentYear}`)
    .then(response => response.json())
    .then(data => {
      console.log('Received status ', data);
      statusData = data;

      updateRecommendedCropIcon();
      updateIconInput();
      
      date.setDate(1);
      const firstDay = new Date(currentYear, currentMonth, 1);
      const lastDay = new Date(currentYear, currentMonth + 1, 0);
      const lastDayIndex = lastDay.getDay();
      const lastDayDate = lastDay.getDate();
      const prevLastDay = new Date(currentYear, currentMonth, 0);
      const prevLastDayDate = prevLastDay.getDate();
      const nextDays = 7 - lastDayIndex - 1;

      month.innerHTML = `${months[currentMonth]} ${currentYear}`;

      let days = "";

      for (let x = firstDay.getDay(); x > 0; x--) {
        days += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
      }

      for (let i = 1; i <= lastDayDate; i++) {
        const dayStatusData = statusData[i] || { month: currentMonth + 1, year: currentYear, status: 'White' }; // Default status to 'White' if not recognized

        let bgColor = '';
        switch (dayStatusData.status) {
          case 'Green':
            bgColor = '#228B22';//#F9EF97 old color
            break;
          case 'Red':
            bgColor = '#F9EF97'; //#cc5858 old color
            break;
          case 'Yellow':
            bgColor = '#39e664';//#65C56D old color
            break;
          default:
            bgColor = 'white'; // Default color if status is not recognized
        }

        const clickableClass = dayStatusData.status === 'Green' ? 'clickable' : '';
        if (
          i === new Date().getDate() &&
          currentMonth === new Date().getMonth() &&
          currentYear === new Date().getFullYear()
        ) {
          days += `<div class="day today ${clickableClass}" style="background-color: ${bgColor}" data-day="${i}" data-month="${currentMonth}">${i}</div>`;
        } else {
          days += `<div class="day ${clickableClass}" style="background-color: ${bgColor}" data-day="${i}" data-month="${currentMonth}">${i}</div>`;
        }
      }

      for (let j = 1; j <= nextDays; j++) {
        days += `<div class="day next">${j}</div>`;
      }

      hideTodayBtn();
      daysContainer.innerHTML = days;

      const clickableDays = document.querySelectorAll('.day.clickable');
      clickableDays.forEach(clickableDay => {
        clickableDay.addEventListener('click', handleDayClick);
      });
    })
    .catch(error => {
      console.error('Error fetching status ', error);
      hideTodayBtn();
      daysContainer.innerHTML = "";
    });
}

function calculateClickedDate(dayNumber, target) {
  const clickedDate = new Date(currentYear, target.getAttribute("data-month"), dayNumber);
  clickedDate.setDate(dayNumber);

  return clickedDate;
}

function handleDayClick(event) {
  const target = event.target;
  const dayNumber = target.getAttribute("data-day");

  const clickedDate = calculateClickedDate(dayNumber, target);

  const harvestDate = new Date(clickedDate);
  harvestDate.setDate(clickedDate.getDate() + 105);

  const modalBody = `Predicted date of harvest: ${formatDateDisplay(harvestDate)}.`;

  $("#exampleModal").modal("show");
  $("#exampleModalLabel").text(`${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);
  $("#modalBody").text(modalBody);
  
}

function updateRecommendedCropIcon() {
  const currentMonthName = months[currentMonth];
  
  console.log(`Current Month: ${currentMonthName}`);

  if (RICE_CROP_MONTHS.includes(currentMonth)) {
    recommendedCropIcon.src = "./img/rice.png";
    console.log(`Recommended Crop Icon: rice.png`);
  } else if (CORN_CROP_MONTHS.includes(currentMonth)) {
    recommendedCropIcon.src = "./img/corn.png"; 
    console.log(`Recommended Crop Icon1: corn.png`);
  } else if (currentMonth === 3) {
    recommendedCropIcon.src = "";
    recommendedCropIcon.alt = "No crop during offseason.";
     console.log("No recommended crop icon1 for April.");
  }
}

function updateIconInput(month) {
  // var currentMonth = month;
  var foundCrop = false;

  fetch('get_thresholds.php')
      .then(response => response.json())
      .then(data => {
        console.log('Received data:', data); // Debugging statement
        if (data && data.length > 0) {
          data.forEach(function(threshold) {
              var cropName = threshold.crop_name;
              var months = threshold.months;

              if (months.includes(String(currentMonth))) {
                  var imageSrc = "./img/" + cropName.toLowerCase() + ".png";
                  $('#icon-input img').attr('src', imageSrc);
                  foundCrop = true;
                  console.log(" " + cropName + " is the crop for month " + currentMonth + ".");
                  return;
              }
          });
          if (!foundCrop) {
              $('#icon-input img').attr('src', ''); // Reset icon if no crop found
              console.log("No crop found for the current month (" + currentMonth + ").");
          }

        } else {
          console.log('No data received from server.'); // Debugging statement
      }
    })
    .catch(error => console.error('Error fetching thresholds: ', error));
}


function formatDateDisplay(date) {
  const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
}

function hideTodayBtn() {
  if (
    currentMonth === new Date().getMonth() &&
    currentYear === new Date().getFullYear()
  ) {
    todayBtn.style.display = "none";
  } else {
    todayBtn.style.display = "flex";
  }
}

renderCalendar();


// Event Listeners
nextBtn.addEventListener("click", () => {
  currentMonth++;
  if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }
  renderCalendar();
});

prevBtn.addEventListener("click", () => {
  currentMonth--;
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }
  renderCalendar();
});

todayBtn.addEventListener("click", () => {
  currentMonth = date.getMonth();
  currentYear = date.getFullYear();
  renderCalendar();
});

