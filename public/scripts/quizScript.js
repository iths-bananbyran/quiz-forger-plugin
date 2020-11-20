class QuizController {
  constructor() {
    this.count = 0;
    this.score = {
      correct: 0,
      wrong: 0,
      percentage: 0
    }
    this.message = {
      perfect: 'Du kunde verkligen det här! Bra jobbat!',
      great: 'Wow, vi är impade! Du hade nästan alla rätt!',
      good: 'Bra jobbat! Du hade mer än hälften rätt.',
      moderate: 'Hm, du kanske borde läsa på lite innan du tar det här quizet en gång till. Du kan bättre! =)',
      poor: 'Det här var illa. Du kanske ska prova ett quiz i ett ämne som intresserar dig mer?',
    }
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

  getCurrentCard() { return this.fullQuiz[this.count] }
  
  getInfoContainer() { return this.currentCard.querySelector('.qf-num-of-questions')}

  getCurrentOptions() { return this.currentCard.querySelectorAll('.qf-answers') }
  
  getCurrentNextQestionButton() { return this.currentCard.querySelector('.qf-next-btn') }
  
  getCurrentExplanation() {
     const exp = this.currentCard.querySelector('.qf-explanation-wrapper') 
     return exp ? exp : '';
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

    this.score.percentage = this.calculatePercentage()

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

  createElement(elem, style) {
    const element = document.createElement(elem)
    element.classList.add(style)
    return element
  }

  calculatePercentage() {
    const result = (this.score.correct / this.quizLength ) * 100

    return result.toFixed(1)
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

  resultMessage() {
    if (this.score.percentage >= 95) return this.message.perfect
    if (this.score.percentage >= 80) return this.message.great
    if (this.score.percentage > 50) return this.message.good
    if (this.score.percentage >= 20) return this.message.moderate
    if (this.score.percentage < 20) return this.message.poor
  }

  handleResult() {
    this.currentCard.innerHTML = ''
    this.currentCard.classList.add('qf-card')
    let header = this.createElement('div', 'qf-question')
    let headerP = this.createElement('p', 'qf-p')
    headerP.innerHTML = `Du fick ${this.score.correct} rätt av ${this.quizLength} möjliga`
    header.append(headerP)
    let resultTextWrapper = this.createElement('div', 'qf-options')
    let resultText = this.createElement('p', 'qf-p')
    resultTextWrapper.append(resultText)
    resultText.innerHTML = `Du svarade ${this.score.percentage}% rätt. <p> ${this.resultMessage()} </p>` 
    this.currentCard.append(header, resultTextWrapper);
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