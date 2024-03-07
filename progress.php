<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main">

        <ul class="julious">
            <li>
            <i class="fa fa-retweet" aria-hidden="true"></i>
                <div class="progresss one">
                    <p>1</p>
                    <i class="uil uil-check"></i>
                </div>
                <p class="text">Data Retrieval</p>
            </li>
            <li >
            <i class="fa fa-spinner" aria-hidden="true"></i>
                <div class="progresss two">
                    <p>2</p>
                    <i class="uil uil-check"></i>
                </div>
                <p class="text">Model Loading</p>
            </li>
            <li >
            <i class="fa fa-book" aria-hidden="true"></i>
                <div class="progresss three">
                    <p>3</p>
                    <i class="uil uil-check"></i>
                </div>
                <p class="text">Sensor Reading Prediction</p>
            </li>
            <li>
            <i class="fa fa-database" aria-hidden="true"></i>
                <div class="progresss four">
                    <p>4</p>
                    <i class="uil uil-check"></i>
                </div>
                <p class="text">Database Update</p>
            </li>
            <li>
            <i class="fa fa-list-alt" aria-hidden="true"></i>
                <div class="progresss five">
                    <p>5</p>
                    <i class="uil uil-check"></i>
                </div>
                <p class="text">Process Complete</p>
            </li>
        </ul>

    </div>




    <script>
const one = document.querySelector(".one");
const two = document.querySelector(".two");
const three = document.querySelector(".three");
const four = document.querySelector(".four");
const five = document.querySelector(".five");

const progressSteps = document.querySelectorAll(".progresss");
let currentStep = 0;

function activateStep(stepIndex) {
    for (let i = 0; i < progressSteps.length; i++) {
        if (i <= stepIndex) {
            progressSteps[i].classList.add("active");
        } else {
            progressSteps[i].classList.remove("active");
        }
    }
}

function autoAdvance() {
    // Increment currentStep and activate the next step with a slight delay
    currentStep = (currentStep + 1) % progressSteps.length;
    setTimeout(() => activateStep(currentStep), 100);
}

// Initial activation (optional)
activateStep(currentStep);

// Set interval to auto advance every 2 seconds
const intervalId = setInterval(autoAdvance, 2000);
</script>

</body>
</html>

<style>

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
@font-face {
    font-family: pop;
    src: url(./Fonts/Poppins-Medium.ttf);
}

.main{
    width: 100%;
    /* height: 100vh; */
    display: flex;
    justify-content: center;
    align-items: flex-start;
    font-family: pop;
    flex-direction: column;
}
.head{
    text-align: center;
}
.head_1{
    font-size: 30px;
    font-weight: 600;
    color: #333;
}
.head_1 span{
    color: #ff4732;
}
.head_2{
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-top: 3px;
}
.julious{
    display: flex;
    margin-top: 30px;
    align-items: flex-start;
}
.julious li{
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.julious li .fa{
    font-size: 35px;
    color: #ffd700;
    margin: 0 60px;
}
.julious li .text{
    font-size: 14px;
    font-weight: 600;
    color: #ff4732;
}

/* Progress Div Css  */
.progresss {
    background-color: #006400; /* Dark Green */
}
.julious li .progresss{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: rgba(68, 68, 68, 0.781);
    margin: 14px 0;
    display: grid;
    place-items: center;
    color: #fff;
    position: relative;
    cursor: pointer;
        transition: background-color 2s ease-in-out, width 2s ease-in-out;

}
.progresss::after{
    content: " ";
    position: absolute;
    width: 125px;
    height: 5px;
    background-color: rgba(68, 68, 68, 0.781);
    right: 30px;
}
.one::after{
    width: 0;
    height: 0;
}
.julious li .progresss .uil{
    display: none;
}
.julious li .progresss p{
    font-size: 13px;
    color: #ffffff; /* White */
    text-align: center;
    line-height: 30px;
}

.active {
    background-color: #ffd700; /* Gold */
    
}

.julious li .active{
    background-color: green;
    display: grid;
    place-items: center;
}
li .active::after{
    background-color: #ffd700;
}
.julious li .active p{
    display: none;
}
.julious li .active .uil{
    font-size: 20px;
    display: flex;
}



</style>
