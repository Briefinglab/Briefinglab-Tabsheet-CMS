(function($){

	$(window).load(function(){

		//js code for admin
		if($('#content').length > 0 ){
			var editor_ex_wrap = $('.postarea.wp-editor-expand').hide();
			var editor_ex = $('#content');
			var new_editor_ex = editor_ex_wrap.after('<div id="tabsheet" class="categorydiv"><h3>Gestione Informazioni</h3><div class="inside"><ul id="tabsheet-tabs" class="category-tabs"></ul></div></div>').next();
			var datatab = {};

			if(editor_ex.val().match(/\[tabsheet id=\"(.*?)\"\](.*)\[\/tabsheet\]/) != null ){
				var val_array = editor_ex.val().split('[tabsheet ');
				if(val_array.length > 0)
				$.each(val_array,function(k,v){
					var tab_content = ('[tabsheet '+v).split(/\[tabsheet id=\"(.*?)\"\](.*)\[\/tabsheet\]/);
					datatab[tab_content[1]] = tab_content[2];
				});
				
			}


			$.each(tabsheet_tablist.split(';'),function(k,v){
				var idtab = v.replace(/ /g, '-');
				//load
				
				new_editor_ex.children('.inside').children('ul')
					.append('<li class="'+(!k?'tabs':'hide-if-no-js')+'"><a href="#txtdiv-'+ idtab +'"><b>'+v+'</b></a></li>')
					.after('<div class="tabs-panel" id="txtdiv-'+ idtab +'"><textarea name="'+ idtab +'" class="attachmentlinks" id="txta_'+ idtab +'">'+(datatab[idtab]?datatab[idtab]:'')+'</textarea></div>');
			});

			//load data

			new_editor_ex.prepend('<small>* Descrizioni dettagli Modello</small>');

			/*Effect tabs*/
			$('#tabsheet-tabs>li>a').each(function(){
				$(this).on('click',function(e){
					var tabtxta = $($(this).attr('href'));
					$(this).parent().addClass('tabs').siblings().removeClass('tabs');
					if(tabtxta.children('textarea').length > 0)
						tabtxta.children('textarea').wp_editor({ content_css:false });
					//
					

					tabtxta.show().siblings('div').hide();

					e.preventDefault();
				});
			}).filter(':first').trigger('click');


			//save data
			editor_ex.parents('form:first').bind('submit',function(){
				editor_ex.val(editor_ex.val().replace('/\[tabsheet (.*)\](.*)\[\/tabsheet\]/gm',''));
				new_editor_ex.find('textarea').each(function(){
					editor_ex
						.val( editor_ex.val() + '[tabsheet id="'+($(this).attr('id').substr(5))+'"]' + ($(this).val().replace(/(\[)/ig,'&#91;')) + '[/tabsheet]');

				});
				return true;
			});
			

		} // endif riassunto */
		
	});
})(jQuery);
