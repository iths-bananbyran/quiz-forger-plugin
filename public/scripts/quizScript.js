
class QuizController {
  constructor() {
    this.count = 0;
    this.score = {correct: 0, wrong: 0}
    this.user = 'unkown'
    this.fullQuiz = document.querySelectorAll('.qf-card-container')
    this.quizLength = this.fullQuiz.length
    this.questionNr = this.count + 1;
    this.currentCard = this.getCurrentCard()
    this.infoContainer = this.getInfoContainer()
    this.currentOptions = this.getCurrentOptions()
    this.nextQuestionBtn = this.getCurrentNextQestionButton()
    this.explanation = this.getCurrentExplanation()
    this.truth = this.getTruth()
    this.userClicked = false;
    this.selectedOption = '';
    this.quizEnd = this.checkIfEnd()
  }

  toggleVisibility(element) { element.classList.toggle('qf-hidden') }

  getCurrentCard(){ return this.fullQuiz[this.count] }
  
  getInfoContainer() { return this.currentCard.childNodes[0].childNodes[0].nextSibling.childNodes[3]}

  getCurrentOptions(){ return this.fullQuiz[this.count].childNodes[1].childNodes[1].childNodes }
  
  getCurrentNextQestionButton(){ return this.currentCard.querySelector('.qf-next-btn') }
  
  getCurrentExplanation(){
     const exp = this.currentCard.querySelector('.qf-explanation-wrapper') 
     return exp ? exp : '';
  }
  
  updateInfoContainer() {
    this.infoContainer.innerHTML = this.questionNr + ' / ' + this.quizLength;
  }

  checkIfEnd() {
    if (this.count === this.quizLength - 1) {
      return true;
    } else {
      return false;
    }
  }

  getTruth() {
    // Add an id to each answer starting from 1
    let truth = {
      wrong: [],
      right: ''
    }
    this.currentOptions.forEach((option, i) => {
      const optionId = i + 1
      option.id = 'option-' + optionId
  
      if(option.dataset.id == 0) {
        truth.wrong.push(option.id)
      } else {
        truth.right = option.id
      }
    })
    return truth
  }

  handleAnswer() {
    // right answer
    if (this.selectedOption.id === this.truth.right) {
      this.score.correct++
      this.applyCss(this.selectedOption, 'qf-right-answer', 'qf-selected', 'qf-right-glow', 'qf-disabled')
      this.currentOptions.forEach(option => {
        if (this.truth.wrong.includes(option.id)) {
          this.applyCss(option, 'qf-disabled', 'qf-opaque')
        }
      })
      // wrong answer
    } else {
      this.score.wrong++
      this.applyCss(this.selectedOption, 'qf-wrong-answer', 'qf-selected', 'qf-wrong-glow', 'qf-disabled')
      this.currentOptions.forEach((option) => {
        if (option.id != this.selectedOption.id && option.id != this.truth.right) {
          this.applyCss(option, 'qf-disabled', 'qf-opaque')
        }
        if (option.id == this.truth.right) {
          this.applyCss(option,'qf-right-answer', 'qf-right-glow', 'qf-disabled')
        }
      })
    }
    
    if (this.explanation) this.toggleVisibility(this.explanation)
    this.addListenerToNextBtn()
    this.toggleVisibility(this.nextQuestionBtn)
  }
  
  addListenersToOptions() {
    this.currentOptions.forEach(option => {
      option.addEventListener('click', () => {
        if (!this.userClicked) {
          this.userClicked = true;
          this.selectedOption = option
          this.handleAnswer()
        }
      })
    })
  }

  addListenerToNextBtn() {
    if (this.quizEnd) {
      this.nextQuestionBtn.innerHTML = 'See resultat'
      this.nextQuestionBtn.addEventListener('click', () => {
        this.handleResult()
      })
    } else {
      this.nextQuestionBtn.addEventListener('click', () => {
        this.handleNext()
      })
    }
  }

  applyCss(elem, ...style) {
    elem.classList.add(...style)
  }
  
  toggleExplanation( ){
    this.explanation.classList.toggle('qf-hidden')
  }
  
  toggleNextBtn() {
    this.nextQuestionBtn.classList.toggle('qf-hidden')
  }

  renderResultBtn() {
    this.nextQuestionBtn.innerHTML = 'Se resultat'
  }
  
  handleNext() {
    if(!this.quizEnd) {
      // Hide visible elements
      this.toggleVisibility(this.currentCard)
      this.toggleVisibility(this.nextQuestionBtn)
      this.explanation ? this.toggleVisibility(this.explanation) : null;
      // Up the count
      this.count++
      // Set the elements to next card
      this.questionNr = this.count + 1;
      this.currentCard = this.getCurrentCard()
      this.infoContainer = this.getInfoContainer()
      this.currentOptions = this.getCurrentOptions()
      this.nextQuestionBtn = this.getCurrentNextQestionButton()
      this.explanation = this.getCurrentExplanation()
      this.truth = this.getTruth()
      this.userClicked = false;
      this.selectedOption = '';
      this.quizEnd = this.checkIfEnd()
      // Show the new card
      this.addListenersToOptions()
      this.updateInfoContainer()
      this.toggleVisibility(this.currentCard)
    }
  }

  handleResult() {
    this.currentCard.innerHTML = '';
    let result = document.createElement('h2')
    result.innerHTML = `Du fick ${this.score.correct} rätt av ${this.quizLength} möjliga`
    this.currentCard.appendChild(result);
  }
  
  initQuiz() {
    this.getTruth()
    this.addListenersToOptions()
    this.updateInfoContainer()
    this.toggleVisibility(this.currentCard)
  }
}

const quiz = new QuizController()
quiz.initQuiz()


// const allCards = document.querySelectorAll('.qf-card-container')
// const numOfQuestions = allCards.length
// let userClicked = false;
// let count = 0;
  

// const getCurrCard = (allCards, c) => { return allCards[c] }
// const getCurrOptions = (allCards, c) => {return allCards[c].childNodes[1].childNodes[1].childNodes}
// const getCardTruth = (options) => {
//   let truth = {
//     wrong: [],
//     right: '',
//     allOptions: options
//   }
//   // Add an id to each answer starting from 1
//   options.forEach((answer, i) => {
//     const answerId = i + 1
//     answer.id = 'option-' + answerId

//     if(answer.dataset.id == 0) {
//       truth.wrong.push(answer.id)
//     } else {
//       truth.right = answer.id
//     }
//   })
//   return truth
// }

// let currCard = getCurrCard(allCards, count)
// let currOptions = getCurrOptions(allCards, count)
// let currTruth = getCardTruth(currOptions)
  
// const addListeners = (truth, userClicked, currCard, c) => {

//   truth.allOptions.forEach(option => {
//     option.addEventListener('click', () => {
//       if (!userClicked) {

//         handleAnswer(option, truth, currCard, c)
  
//       }
//     })
//   })
//   let nextBtn = currCard.querySelector('.qf-next-btn')
//   nextBtn.addEventListener('click', () => {
//     handleNext(allCards, count, currCard)
//   })
// }
  
// const handleAnswer = (selected, truth, currCard) => {
//   // right answer
//   if (selected.id === truth.right) {
//     applyCss(selected, 'qf-right-answer', 'qf-selected', 'qf-right-glow', 'qf-disabled')
//     truth.allOptions.forEach(option => {
//       if (truth.wrong.includes(option.id)) {
//         applyCss(option, 'qf-disabled', 'qf-opaque')
//       }
//     })
//     // wrong answer
//   } else {
//     applyCss(selected, 'qf-wrong-answer', 'qf-selected', 'qf-wrong-glow', 'qf-disabled')

//     truth.allOptions.forEach((option) => {
//       if (option.id != selected.id && option.id != truth.right) {
//         applyCss(option, 'qf-disabled', 'qf-opaque')
//       }
//       if (option.id === truth.right) {
//         applyCss(option,'qf-right-answer', 'qf-right-glow', 'qf-disabled')
//       }
//     })
//   }
//   toggleExplanation(currCard)
//   toggleNextBtn(currCard)
// }
  
// const applyCss = (elem, ...style) => {
//   elem.classList.add(...style)
// }

// const toggleExplanation = (currCard) => {
//   const explanation = currCard.querySelector('.qf-explanation-wrapper')
//   explanation.classList.toggle('qf-hidden')
// }

// const toggleNextBtn = (currCard) => {
//   const nextBtn = currCard.querySelector('.qf-next-btn')
//   nextBtn.classList.toggle('qf-hidden')
// }

// const handleNext = (cards, count, hideCard) => {
//   console.log('handleNext: ', userClicked);
//   userClicked = true;
//   if (userClicked) {
//     userClicked = false;
//     count++
//     currCard = ''
//     currOptions = ''
//     currTruth = ''
//     currCard = getCurrCard(cards, count)
//     currOptions = getCurrOptions(cards, count)
//     currTruth = getCardTruth(currOptions)
//     addListeners(currTruth, userClicked, currCard, count);
//     toggleVisibility(hideCard)
//     toggleVisibility(currCard)
//   }
// }
// addListeners(currTruth, userClicked, currCard, count);
// toggleVisibility(allCards[count]);

// }
// truth, userClicked, currCard, c