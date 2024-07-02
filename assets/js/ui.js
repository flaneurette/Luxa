var plainui = { 

	post: function() {
		
		let c = document.getElementById('count').value;
		for(let i=0; i < c; i++) {
			this.proc('texteditor-' + i,'snippet_text_' + i);
		}
		
		document.getElementById('form').submit();
	},

	proc: function(from,to) {
	var text = document.getElementById(from).innerHTML;
		document.getElementById(to).value = text;
	},

};