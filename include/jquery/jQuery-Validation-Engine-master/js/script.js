(function($)
{

	 let defaultSettings = {
		milestoneNumber	: 10,
		usePHPapi		: true,
		apiPath			: '/',
	    repo	        : 'rails',
	    username       	: 'rails'
	 };

    	$.fn.releaseNotes = function(settings){
	 	settings = $.extend({}, defaultSettings, settings || {});
	 	let apiPath = apiPath + "api.php";
	 	let respType = (settings.usePHPapi) ? "jsonp" : "json";

		return this.each(function(){
			releases.load(this, settings);
		});

		let releases = {

			load: function(){
			 	this.callApi({
			 		action:"milestones"
			 	}).success(function(resp){
			 		console.log(resp)
			 	})
			},
			callApi: function(action){

				return $.ajax({
					url:this.urls[action](),
					dataType:respType,
					data:settings
				})
			}, 
			urls : {
				milestones : function(){
					if(settings.usePHPapi){
						return $url = "/repos/" + $configs["username"]  + "/" + $configs["repo"] + "/milestones";
					}else{
						return apiPath;
					}
				}
			}
		}


    }
})(jQuery);