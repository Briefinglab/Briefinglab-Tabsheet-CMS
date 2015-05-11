(function($){

	$.fn.tinymce_textareas = function(){
	  tinyMCE.init({
	    skin : "wp_theme"
	    // other options here
	  });
	};

	$(window).load(function(){

		//js code for admin
		if($('#content').length > 0){
			var editor_ex_wrap = $('.postarea.wp-editor-expand').hide();
			var editor_ex = $('#content');
			var new_editor_ex = editor_ex_wrap.after('<div id="tabsheet" class="categorydiv"><h3>Gestione Informazioni</h3><div class="inside"><ul id="tabsheet-tabs" class="category-tabs"></ul></div></div>').next();
			var data = {};

			$.each(tabsheet_tablist.split(';'),function(k,v){
				new_editor_ex.children('.inside').children('ul')
					.append('<li class="'+(!k?'tabs':'hide-if-no-js')+'"><a href="#txtdiv-'+ v.replace(/ /g, '-') +'"><b>'+v+'</b></a></li>')
					.after('<div '+(k?'style="display:none"':'')+' class="tabs-panel" id="txtdiv-'+ v.replace(/ /g, '-') +'"><textarea class="attachmentlinks" id="txta_'+ v.replace(/ /g, '-') +'"></textarea></div>');
			});

			//load data
			if(editor_ex.val().length > 0){
				var val = JSON.parse(editor_ex.find('textarea').val());
				if(!$.isEmptyObject(val)){
					$.each(val,function(k,v){ 
						new_editor_ex.find('textarea[ref='+k+']').text(v.slice(1,-1).replace(/(\\")/g,'"'));
					});
				}
			}

			new_editor_ex.find('textarea').on('change',function(){ //.on('input propertychange',function(){ 
					new_editor_ex.find('textarea').each(function(){
						data[$(this).attr('ref')] = JSON.stringify($(this).val());
					});

				editor_ex.find('textarea').val(JSON.stringify(data));
					
			});


			new_editor_ex.prepend('<small>* Descrizioni dettagli Modello</small>');



			/*Effect tabs*/
			$('#tabsheet-tabs>li>a').on('click',function(e){
				$(this).parent().addClass('tabs').siblings().removeClass('tabs');
				$($(this).attr('href')).show().siblings('div').hide();

				//$($(this).attr('href')).show().siblings().hide(); //.not($($(this).attr('href'))).hide();

				e.preventDefault();
			}).each(function(){ 
				$($(this).attr('href')).children('textarea').wp_editor({textarea_rows:20});
			});

		} // endif riassunto */
		



	});
})(jQuery);


function addTinyText(id){
//add textarea to DOM
//init tineMCE
 tinyMCE.init({
        theme : "advanced",
        plugins : "emotions,spellchecker"
});
//add tinymce to this
tinyMCE.execCommand("mceAddControl", false, id);
}