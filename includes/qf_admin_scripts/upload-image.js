window.addEventListener('load', ()=>{

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
			button.innerHTML = `${attachment.filename} was uploaded. Click here to change/remove.`;
			img.value = attachment.url;
		}).open();
    
	})



})
