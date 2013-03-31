var canvas = document.getElementById("logoCanvas");
var ctx = canvas.getContext("2d");

var OUTER_STAR = "/templates/ecc.ck.ua/images/out_star.png";
var OUTER_NOTE = "/templates/ecc.ck.ua/images/out_note.png";
var OUTER_GCIRCLE = "/templates/ecc.ck.ua/images/out_gcircle.png";
var OUTER_PCIRCLE = "/templates/ecc.ck.ua/images/out_pcircle.png";

var outer_sparkles = [
	function(){draw_image(OUTER_GCIRCLE, 45, 27)},
	function(){draw_image(OUTER_NOTE, 23, 20)},
	function(){draw_image(OUTER_GCIRCLE, 14, 33)},
	function(){draw_image(OUTER_STAR, 4, 10)},
	function(){draw_image(OUTER_PCIRCLE, 18, 2)},
	function(){
		ctx.clearRect(0, 0, 59, 38);
		ctx.clearRect(0, 38, 33, 45);
	}
]

var OUTER_SPARKLES = outer_sparkles.length;
var outer_frame = 0;

var draw_image = function(shape_src, x, y){
	var img = new Image();
	img.src = shape_src;
	img.onload = function(){
		ctx.drawImage(img, x, y);
	}
}

var animate_outer_sparkles = function(){
	if(outer_frame >= OUTER_SPARKLES){
		outer_frame = 0;
	}
	outer_sparkles[outer_frame]();
	outer_frame++;
};

$(document).ready(function() {
	var bgImg = new Image();
	bgImg.src = "/templates/ecc.ck.ua/images/lamp.png";
	bgImg.onload = function() {
		ctx.drawImage(bgImg, 0, 0);
		window.setInterval(animate_outer_sparkles, 150);
	};
});
