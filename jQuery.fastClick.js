/**
 * jQuery.fastClick.js
 * 
 * Work around some mobile browser's 300ms delay on the click event.
 * 
 * Code based on <http://code.google.com/mobile/articles/fast_buttons.html>
 * Warning will break href contained in the element bound
 * @usage
 * $('button').fastClick(function() { });
 * 
 * @license Under Creative Commons Attribution 3.0 License
 * @author Morgan Laupies
 * @version 0.2 2011-09-20
 */

/*global document, window, jQuery, Math */

(function($) {
	
	var clickBuster = {};
	clickBuster.coordinates = [];
	clickBuster.preventGhostClick = function(x, y) {
		  clickBuster.coordinates.push(x, y);
		  window.setTimeout(clickBuster.pop, 2500);
	};
	clickBuster.pop = function() {
		  clickBuster.coordinates.splice(0, 2);
	};
	clickBuster.onClick = function(event) {
		  for (var i = 0; i < clickBuster.coordinates.length; i += 2) {
		    var x = clickBuster.coordinates[i];
		    var y = clickBuster.coordinates[i + 1];
		    if (Math.abs(event.clientX - x) < 25 && Math.abs(event.clientY - y) < 25) {
		      event.stopPropagation();
		      event.preventDefault();
		    }
		  }
	};
	document.addEventListener('click', clickBuster.onClick, true);
	
	

 var FastButton = function(element, handler) {
	
	 
	 /// Remove previous event listener ( Merge them ? )
	 this.element = element;
	 this.handler = handler;
	 
	 element.addEventListener('touchstart', this, false);
	 element.addEventListener('click', this, false);
	
};

FastButton.prototype.handleEvent = function( event ){
	switch (event.type) {
	    case 'touchstart': this.onTouchStart(event); break;
	    case 'touchmove': this.onTouchMove(event); break;
	    case 'touchend': this.onClick(event); break;
	    case 'click': this.onClick(event); break;
  }
};

FastButton.prototype.onTouchStart = function(event) {
  event.stopPropagation();

  this.element.addEventListener('touchend', this, false);
  document.body.addEventListener('touchmove', this, false);

  this.startX = event.touches[0].clientX;
  this.startY = event.touches[0].clientY;
};

FastButton.prototype.onTouchMove = function(event) {
	  if (Math.abs(event.touches[0].clientX - this.startX) > 10 ||
	      Math.abs(event.touches[0].clientY - this.startY) > 10) {
	    this.reset();
	  }
};

FastButton.prototype.onClick = function(event) {
	  event.stopPropagation();
	  this.reset();
	  this.handler.call( this.element , event);

	  if (event.type == 'touchend') {
		  clickBuster.preventGhostClick(this.startX, this.startY);
	  }
};

FastButton.prototype.reset = function() {
	  this.element.removeEventListener('touchend', this, false);
	  document.body.removeEventListener('touchmove', this, false);
};



$.fn.fastClick = function(handler) {
	return $(this).each(function(){
		new FastButton( $(this)[0] , handler );
	});
};
	
}(jQuery));
