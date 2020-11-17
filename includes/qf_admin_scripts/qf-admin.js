window.addEventListener('load', ()=>{

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);

	if (urlParams.get('page')==='add-questions'){

		const uploadBtn = document.querySelector('.upload-question-img');
		let img = document.querySelector('#img-value');
	
		uploadBtn.addEventListener('click', (e)=>{
	
			e.preventDefault();
			
			let button = e.target;
			let customUpload = wp.media({
				title: 'Insert image',
				library : {
					type : 'image'
				},
				button: {
					text: 'Use this image' 
				},
				multiple: false
			}).on('select', ()=> { 
				let attachment = customUpload.state().get('selection').first().toJSON();
				button.innerHTML = `Change/remove?`;
				let imgText = document.createElement('span');
				button.before(imgText);
				imgText.innerHTML = `${attachment.filename} was uploaded.`;
				imgText.classList.add('qf-preamble');
				img.value = attachment.url;
			}).open();
		
		})
	}

	if (urlParams.get('page') ==='Quizforger'){

        console.log('Quiz page from upload-image!')

        let accordionBtns = document.querySelectorAll('.qf-accordion-btn');
    
        accordionBtns.forEach(btn => btn.addEventListener('click', e => {

			let accordionRow = e.target.parentElement.parentElement;
			accordionRow.classList.toggle('qf-active');

			let questionsPanel = accordionRow.nextElementSibling;
			questionsPanel.classList.toggle('qf-hide');
			// if (questionsPanel.style.display === "block") {
			// 	questionsPanel.style.display = "none";
			//   } else {
			// 	questionsPanel.style.display = "block";
			//   }
		}))
    }




})
