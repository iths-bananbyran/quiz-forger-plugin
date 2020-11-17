window.addEventListener('DOMContentLoaded', init)

function init() {
  const allQuestions = document.querySelectorAll('.qf-card-container')
  const numOfQuestions = allQuestions.length
  const allAnswers = document.querySelectorAll('.qf-answers')
  const wrongAnswers = findAndIdWrongAnswers(allAnswers)
  let currentQuestion = 0;



  const toggleVisibility = (element) => {
    element.classList.toggle('qf-hidden');
  }

  toggleVisibility(allQuestions[currentQuestion])
}

const findAndIdWrongAnswers = (allAnswers) => {
  let arr = []
  // Add an id to each answer starting from 1
  allAnswers.forEach((answer, i) => {
    const answerId = i + 1
    answer.id = 'answer-' + answerId

    answer.addEventListener('click', () => {
      checkAnswer(answerId)
    })

    // Return all the wrong answers
    if(answer.dataset.id == 0) {
      arr.push(answer)
    }
  })
  return arr
}

const checkAnswer = (id) => {
  const selectedAnswer = document.querySelector(`#answer-${id}`)
  if (selectedAnswer.dataset.id === '1') {
    // right answer
    applyCss(selectedAnswer, 'qf-right-answer', 'qf-scale')
  } else {
    // wrong answer
    applyCss(selectedAnswer, 'qf-wrong-answer', 'qf-scale')
    applyCss(selectedAnswer.dataset.id, 'qf-wrong-answer', 'qf-scale')
  }
}

const applyCss = (elem, ...style) => {
  elem.classList.add(...style)
}

const findWrongAnswers = (answers) => {
  
}