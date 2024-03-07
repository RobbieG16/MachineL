const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");

const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

let statusData = {};

const date = new Date();

const days = document.querySelectorAll(".days .day");

daysContainer.addEventListener("click", (event) => {
  const target = event.target;

  if (target.classList.contains("day") && target.classList.contains("clickable")) {
    const dayNumber = target.getAttribute("data-day");

    const clickedDate = calculateClickedDate(dayNumber, target);

    console.log(`Clicked date: ${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);

    const harvestDate = new Date(clickedDate);
    harvestDate.setDate(clickedDate.getDate() + 105);

    const modalBody = `Predicted date of harvest: ${formatDateDisplay(harvestDate)}.`;

    $("#exampleModal").modal("show");
    $("#exampleModalLabel").text(`${months[clickedDate.getMonth()]} ${clickedDate.getDate()}, ${clickedDate.getFullYear()}`);
    $("#modalBody").text(modalBody);

    console.log(`Predicted date of harvest: ${formatDateDisplay(harvestDate)}`);
  }
});

function calculateClickedDate(dayNumber, target) {
  const clickedDate = new Date(currentYear, target.getAttribute("data-month"), dayNumber);
  clickedDate.setDate(dayNumber);

  return clickedDate;
}

function formatDateDisplay(date) {
  const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
}

let currentMonth = date.getMonth();

let currentYear = date.getFullYear();

function renderCalendar() {
  fetch(`fetch_status.php?month=${currentMonth + 1}&year=${currentYear}`)
    .then(response => response.json())
    .then(data => {
      console.log('Received status ', data);
      statusData = data;

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
            bgColor = '#65C56D';
            break;
          case 'Red':
            bgColor = 'red';
            break;
          case 'Yellow':
            bgColor = 'yellow';
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
renderCalendar();

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

renderCalendar();

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