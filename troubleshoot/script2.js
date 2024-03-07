const daysContainer2 = document.querySelector(".days2"),
  nextBtn2 = document.querySelector(".next-btn2"),
  prevBtn2 = document.querySelector(".prev-btn2"),
  month2 = document.querySelector(".month2"),
  todayBtn2 = document.querySelector(".today-btn2");

let statusData2 = {};

const date2 = new Date();

const days2 = document.querySelectorAll(".days2 .day2");

daysContainer2.addEventListener("click", (event) => {
  const target = event.target;

  if (target.classList.contains("day2") && target.classList.contains("clickable2")) {
    const dayNumber = target.getAttribute("data-day");

    const clickedDate = calculateClickedDate(dayNumber, target);

    console.log(`Clicked date (Second Calendar): ${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);

    const harvestDate = new Date(clickedDate);
    harvestDate.setDate(clickedDate.getDate() + 105);

    const modalBody = `Predicted date of harvest: ${formatDateDisplay(harvestDate)}.`;

    $("#exampleModal").modal("show");
    $("#exampleModalLabel").text(`${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);
    $("#modalBody").text(modalBody);

    console.log(`Predicted date of harvest (Second Calendar): ${formatDateDisplay(harvestDate)}`);
  }
});

function calculateClickedDate2(dayNumber, target) {
  const clickedDate = new Date(currentYear2, target.getAttribute("data-month2"), dayNumber);
  clickedDate.setDate(dayNumber);

  return clickedDate;
}

function formatDateDisplay2(date) {
  const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
}

let currentMonth2 = date2.getMonth();

let currentYear2 = date2.getFullYear();

function renderCalendar2() {
  fetch(`fetch_status2.php?month=${currentMonth2 + 1}&year=${currentYear2}`)
    .then(response => response.json())
    .then(data => {
      console.log('Received status data (Second Calendar):', data);
      statusData2 = data;

      date2.setDate(1);
      const firstDay2 = new Date(currentYear2, currentMonth2, 1);
      const lastDay2 = new Date(currentYear2, currentMonth2 + 1, 0);
      const lastDayIndex2 = lastDay2.getDay();
      const lastDayDate2 = lastDay2.getDate();
      const prevLastDay2 = new Date(currentYear2, currentMonth2, 0);
      const prevLastDayDate2 = prevLastDay2.getDate();
      const nextDays2 = 7 - lastDayIndex2 - 1;

      month2.innerHTML = `${months[currentMonth2]} ${currentYear2}`;

      let days2 = "";

      for (let x = firstDay2.getDay(); x > 0; x--) {
        days2 += `<div class="day2 prev2">${prevLastDayDate2 - x + 1}</div>`;
      }

      for (let i = 1; i <= lastDayDate2; i++) {
        const dayStatusData2 = statusData2[i] || { month2: currentMonth2 + 1, year2: currentYear2, status2: 0 };
        //const bgColor2 = dayStatusData2.status === 'Green' ? 'green' : 'red';

        let bgColor2 = '';
        switch (dayStatusData2.status) {
          case 'Green':
            bgColor2 = 'green';
            break;
          case 'Red':
            bgColor2 = 'red';
            break;
          case 'Yellow':
            bgColor2 = 'yellow';
            break;
          default:
            bgColor2 = 'white'; // Default color if status is not recognized
        }

        const clickableClass = dayStatusData2.status === 'Green' ? 'clickable' : '';
        
        if (
          i === new Date().getDate() &&
          currentMonth2 === new Date().getMonth() &&
          currentYear2 === new Date().getFullYear()
        ) {
          const clickableClass2 = dayStatusData2.status === 'Green' ? 'clickable2' : '';
          days2 += `<div class="day2 today2 ${clickableClass2}" style="background-color: ${bgColor2}" data-day="${i}" data-month2="${currentMonth2}">${i}</div>`;
        } else {
          const clickableClass2 = dayStatusData2.status === 'Green' ? 'clickable2' : '';
          days2 += `<div class="day2 ${clickableClass2}" style="background-color: ${bgColor2}" data-day="${i}" data-month2="${currentMonth2}">${i}</div>`;
        }
      }

      for (let j = 1; j <= nextDays2; j++) {
        days2 += `<div class="day2 next2">${j}</div>`;
      }

      hideTodayBtn2();
      daysContainer2.innerHTML = days2;

      const clickableDays2 = document.querySelectorAll('.day2.clickable2');
      clickableDays2.forEach(clickableDay2 => {
        clickableDay2.addEventListener('click', handleDayClick2);
      });
    })
    .catch(error => {
      console.error('Error fetching status data (Second Calendar):', error);
      hideTodayBtn2();
      daysContainer2.innerHTML = "";
    });
}

function handleDayClick2(event) {
  const target = event.target;
  const dayNumber = target.getAttribute("data-day");

  const clickedDate = calculateClickedDate2(dayNumber, target);

  const harvestDate = new Date(clickedDate);
  harvestDate.setDate(clickedDate.getDate() + 105);

  const modalBody = `Predicted date of harvest (Second Calendar): ${formatDateDisplay2(harvestDate)}.`;

  $("#exampleModal").modal("show");
  $("#exampleModalLabel").text(`${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);
  $("#modalBody").text(modalBody);
}

renderCalendar2();

nextBtn2.addEventListener("click", () => {
  currentMonth2++;
  if (currentMonth2 > 11) {
    currentMonth2 = 0;
    currentYear2++;
  }
  renderCalendar2();
});

prevBtn2.addEventListener("click", () => {
  currentMonth2--;
  if (currentMonth2 < 0) {
    currentMonth2 = 11;
    currentYear2--;
  }
  renderCalendar2();
});

todayBtn2.addEventListener("click", () => {
  currentMonth2 = date2.getMonth();
  currentYear2 = date2.getFullYear();
  renderCalendar2();
});

renderCalendar2();

function hideTodayBtn2() {
  if (
    currentMonth2 === new Date().getMonth() &&
    currentYear2 === new Date().getFullYear()
  ) {
    todayBtn2.style.display = "none";
  } else {
    todayBtn2.style.display = "flex";
  }
}