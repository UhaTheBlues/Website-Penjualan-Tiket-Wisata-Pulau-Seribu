const questionNumber = document.querySelector(".question-number");
const questionText = document.querySelector(".question-text");
const optionContainer = document.querySelector(".option-container");
const answersIndicatorContainer = document.querySelector(".answers-indicator");
const homeBox = document.querySelector(".home-box");
const quizBox = document.querySelector(".quiz-box");
const resultBox = document.querySelector(".result-box");
const questionLimit = 2; //Jika ingin menampilkan semua quis menggunakan 'quiz.length'

let questionCounter = 0;
let currentQuestion;
let availableQuestions = [];
let availableOptions = [];
let correctAnswers = 0;
let attempt = 0;

// function ajax(url) {
//   return new Promise(function(resolve, reject) {
//     var xhr = new XMLHttpRequest();
//     xhr.onload = function() {
//       resolve(this.responseText);
//     };
//     xhr.onerror = reject;
//     xhr.open('GET', url);
//     xhr.send();
//   });
// }
$.getJSON('api/jsonkuis.php', function(data){
	

	APP.myData = data;
	
	})
console.log(APP.myData)

function FisherYatesShuffle(pertanyaan) {
  var currentIndex = pertanyaan.length, temporaryValue, randomIndex;

  // Ketika masih ada elemen yang harus diacak
  while (0 !== currentIndex) {

    // Memilih elemen yang tersisa
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // Dan menukar dengan elemen saat ini
    temporaryValue = pertanyaan[currentIndex];
    pertanyaan[currentIndex] = pertanyaan[randomIndex];
    pertanyaan[randomIndex] = temporaryValue;
  }

  return pertanyaan;
}
var quizFisherYates =  FisherYatesShuffle(quiz);
console.log(quizFisherYates);
//console.log(jsonA)

//push pertanyaan ke "availableQuestions" Array
function setAvailableQuestions(){
	const totalQuestion = questionLimit;
	for(let i=0; i<totalQuestion; i++){
		availableQuestions.push(getExchangeRate()[i])
		// console.log(quizFisherYates[i]);
		// console.log(availableQuestions);
	}
}

//set question number and question and options
function getNewQuestion(){
	var current_availableQuestions = 0;
	//set question number
	questionNumber.innerHTML = "Pertanyaan " + (questionCounter + 1) + " dari " + questionLimit;
	
	//set question text
	//get random question
	const questionIndex = availableQuestions[Math.floor(current_availableQuestions++ % availableQuestions.length)]
	currentQuestion = questionIndex;
	questionText.innerHTML = currentQuestion.q;
	// console.log(availableQuestions);

	//get the position of 'questionIndex' from the availableQuestion Array;
	const index1 = availableQuestions.indexOf(questionIndex);
	//remove the 'questionIndex' from the availableQuestion Array; for no repeat question
	availableQuestions.splice(index1,1);
	
	// menampilkan gambar pada pertanyaan jika 'img' properti ada
	if(currentQuestion.hasOwnProperty("img")){
		const img = document.createElement("img");
		img.src = currentQuestion.img;
		questionText.appendChild(img);
	}

	//set options
	//get the length of options
	const optionLen = currentQuestion.options.length
	//push options into availableOptions Array
	for(let i=0; i<optionLen; i++){
		availableOptions.push(i)
	}
	optionContainer.innerHTML = '';
	let animationDelay = 0.2;
	//create options in html
	for(let i=0; i<optionLen; i++){
		//random option
		const optonIndex = availableOptions[Math.floor(Math.random() * availableOptions.length)];
		//get the position of 'optonIndex' from the availableOptions
		const index2 = availableOptions.indexOf(optonIndex);
		//remove the 'optonIndex' from availableOptions Array , no repeat options
		availableOptions.splice(index2,1);
		const option = document.createElement("div");
		option.innerHTML = currentQuestion.options[optonIndex];
		option.id = optonIndex;
		option.style.animationDelay = animationDelay + 's';
		animationDelay = animationDelay + 0.2;
		option.className = "option";
		optionContainer.appendChild(option)
		option.setAttribute("onclick","getResult(this)");
	}
	questionCounter++
}


//get the result of current attempt question
function getResult(element){
	const id = parseInt(element.id);
	//get the answer by comparing the id of clicked option
	if(id === currentQuestion.answer){
		//set the green color to the correct option
		element.classList.add("correct");
		//menambahkan indicator ke jawaban benar
		updateAnswerIndicator("correct");
		correctAnswers++;
	}
	else{
		//set the red color to the wrong option
		element.classList.add("wrong");
		//menambahkan indicator ke jawaban salah
		updateAnswerIndicator("wrong");

		//kalau jawaban salah, memeberikan jawaban benar berwarna hijau
		const optionLen = optionContainer.children.length;
		for(let i=0; i<optionLen; i++){
			if(parseInt(optionContainer.children[i].id) === currentQuestion.answer){
				optionContainer.children[i].classList.add("correct");
			}
		}
	}
	attempt++;
	unclickableOptions();
}

//make all the options unclickable once the user select a option (Tidak bisa mengganti jawaban)
function unclickableOptions(){
	const optionLen = optionContainer.children.length;
	for(let i=0; i<optionLen; i++){
		optionContainer.children[i].classList.add("already-answered");
	}
}

function answersIndicator() {
	answersIndicatorContainer.innerHTML = '';
	const totalQuestion = questionLimit;
	for(let i=0; i<totalQuestion; i++){
		const indicator = document.createElement("div");
		answersIndicatorContainer.appendChild(indicator);
	}
}
function updateAnswerIndicator(markType){
	answersIndicatorContainer.children[questionCounter-1].classList.add(markType)
}
function next(){
	if(questionCounter === questionLimit){
		quizOver();
	}else{
		getNewQuestion();
	}
}

function quizOver(){
	//menyembunyikan quizBox
	quizBox.classList.add("hide");
	//menampilkan result box
	resultBox.classList.remove("hide");
	quizResult();
}

//mendapatkan hasil kuis
function quizResult(){
	resultBox.querySelector(".total-question").innerHTML = questionLimit;
	resultBox.querySelector(".total-attempt").innerHTML = attempt;
	resultBox.querySelector(".total-correct").innerHTML = correctAnswers;
	resultBox.querySelector(".total-wrong").innerHTML = attempt - correctAnswers;
	const percentage = (correctAnswers / questionLimit)*100;
	resultBox.querySelector(".percentage").innerHTML = percentage.toFixed(2) + "%";
	resultBox.querySelector(".total-score").innerHTML = correctAnswers + " / " + questionLimit;
}

function resetQuiz(){
	questionCounter = 0;
	correctAnswers = 0;
	attempt = 0;
	availableQuestions = [];
}
function tryAgainQuiz(){
	//menyembunyikan result box
	resultBox.classList.add("hide");
	//menampilkan quiz box
	quizBox.classList.remove("hide");

	resetQuiz();
	startQuiz();
}

function goToHome(){
	//menyembunyikan result box
	resultBox.classList.add("hide");
	//menampilkkan home box
	homeBox.classList.remove("hide");
	resetQuiz();
}
// starting point
function startQuiz(){

	// menyembunyikan home box
	homeBox.classList.add("hide");
	//menampilkan quiz box
	quizBox.classList.remove("hide");
	//first we will set all questions in availableQuestion Array
	setAvailableQuestions();
	//second we will call getNewQuestion(); function
	getNewQuestion();
	//to create indicator of answers
	answersIndicator();
	FisherYatesShuffle(quiz);
}

function klikMulai(){
	//menampilkan homebox
	homeBox.classList.remove("hide");
}


window.onload = function(){
	homeBox.querySelector(".total-question").innerHTML = questionLimit;
}
