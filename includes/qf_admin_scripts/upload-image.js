window.addEventListener('load', ()=>{

    console.log('working!')

    const uploadBtn = document.querySelectorAll('.upload-question-img');

    uploadBtn.forEach(btn => btn.addEventListener('click', (e)=>{

    
        e.preventDefault();
        let button = e.target;
        let customUpload = wp.media({
            title: 'Insert image',
            library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', ()=> { // it also has "open" and "close" events
			let attachment = customUpload.state().get('selection').first().toJSON();
			button.innerHTML = `<img src="' + ${attachment.url} + '">` //).next().val(attachment.id).next().show()`;
		}).open();

        console.log(customUpload);
    
    }))

})
