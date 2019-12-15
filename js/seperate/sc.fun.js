//Something fun :)
var hasStarted = false;
var snow = {

	// Opts
	options : {
		flakeCount : 35,
    flakeColor : '#FFF',
    flakeIndex: 999999,
    flakePosition: 'fixed',
    minSize : 4,
    maxSize : 10,
    minSpeed : 1,
    maxSpeed : 5,
    round : true,
    shadow : true
	},

	// Instructions
	init: function() 
	{
  	console.log('Oh hello there...');
  	console.log('I take it you\'re a bit bored?');
  	console.log('Don\'t you think it would be cool if it started to snow?');
  	console.log('Why don\'t you type "snow.start()"');
	},

	// Methods
	start: function()
	{
		$('body').snowfall(this.options);
		if (! hasStarted) {
  		hasStarted = true;
  		console.log('WOAH, it\'s snowing!');
  		console.log('Wouldn\'t it be cool if you could control it?');
  		console.log('Type "snow.instructions()" to find out the controls');
		}
	}, 

	stop: function() 
	{
		$('body').snowfall('clear');
	},

	reset: function() 
	{
		$('body').snowfall('clear');
		$('body').snowfall(this.options);

		if (this.options.flakeCount == 500) 
  		console.log('WOAH, that is a lot of snow!');
	},

	instructions: function () 
	{
		console.log('Below is the object, I assume you will know what this is...');
		console.log(this);
		console.log('Let\'s try an example');
		console.log('Type "snow.options.flakeCount = 500"');
		console.log('Type "snow.reset()"');
	}
};