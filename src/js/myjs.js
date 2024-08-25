$(document).ready(function(){
	// Logic for home title
	$('.highlight').addClass('animate-highlight');

	// Logic for home image
	let images = [
		'img-home1.jpg',
		'img-home2.jpg',
		'img-home3.jpg',
		'img-home4.jpg'
	];

	let randomIndex = Math.floor(Math.random() * images.length);
	let selectedImage = images[randomIndex];
	let imageUrl = '/src/img/' + selectedImage;

	// Create a new Image object to pre-load the image
	let img = new Image();
	img.src = imageUrl;

	// When the image has loaded, update the src attribute of the img element and show it
	img.onload = function() {
		// Update src and show the image with a fade-in effect
		$('#homeImage').attr('src', imageUrl).fadeIn();
	};

	// Handle the error case
	img.onerror = function() {
		console.error('Failed to load image:', imageUrl);
	};
});

