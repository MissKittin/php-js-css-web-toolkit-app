function format_readme(input, output)
{
	if(output === null)
		return false;

	var markdown=input.innerHTML.split('\n');
	var html='';
	var code_block_opened=false; var ul_list_opened=false; var ol_list_opened=false;

	for(var i=0; i<markdown.length; i++)
	{
		// headers
		if(markdown[i].substring(0, 3) === '###')
			html+='<h3>'+markdown[i].slice(4)+'</h3>';
		else if(markdown[i].substring(0, 2) === '##')
			html+='<h2>'+markdown[i].slice(3)+'</h2>';
		else if(markdown[i].substring(0, 1) === '#')
			html+='<h1>'+markdown[i].slice(2)+'</h1>';

		// code blocks
		else if(markdown[i].trim().substring(0, 3) === '```')
		{
			if(code_block_opened)
			{
				html+='</div>';
				code_block_opened=false;
			}
			else
			{
				html+='<div style="font-family: monospace; font-weight: bold; white-space: pre; overflow: auto; border-radius: 5px; color: #000000; background-color: #aaaaaa; margin-top: 5px; margin-bottom: 5px; padding-top: 20px; padding-bottom: 20px; padding-left: 5px; padding-right: 5px;">';
				code_block_opened=true;
			}
		}

		// lists opening
		else if((!isNaN(markdown[i].substring(0, 1))) && (markdown[i].substring(1, 2) === ')'))
		{
			if(!ol_list_opened)
			{
				html+='<ol>';
				ol_list_opened=true;
			}

			html+='<li>'
				+markdown[i]
				.slice(3)
				.replace('  ', '<br>')
				.replace(/`(.*?)`/g, '<span style="font-family: monospace; font-weight: bold; border-radius: 5px; color: #000000; background-color: #aaaaaa; padding: 2px;">$1</span>')
				+'</li>';
		}
		else if(markdown[i].substring(0, 2) === '* ')
		{
			if(!ul_list_opened)
			{
				html+='<ul>';
				ul_list_opened=true;
			}

			html+='<li>'
				+markdown[i]
				.slice(2)
				.replace('  ', '<br>')
				.replace(/`(.*?)`/g, '<span style="font-family: monospace; font-weight: bold; border-radius: 5px; color: #000000; background-color: #aaaaaa; padding: 2px;">$1</span>')
				+'</li>';
		}

		// regular text/lists closing/code blocks linebreaks
		else
		{
			if(markdown[i].substring(0, 1) !== "\t")
			{
				if(ol_list_opened)
				{
					html+='</ol>';
					ol_list_opened=false;
				}
				if(ul_list_opened)
				{
					html+='</ul>';
					ul_list_opened=false;
				}
			}

			if(code_block_opened)
				html+=markdown[i].trimStart()+'<br>';
			else
				html+=markdown[i]
					.trimStart()
					.replace('  ', '<br>')
					.replace(/`(.*?)`/g, '<span style="font-family: monospace; font-weight: bold; border-radius: 5px; color: #000000; background-color: #aaaaaa; padding: 2px;">$1</span>');
		}
	}

	output.innerHTML=html.replace(/\[([^\]]+)\]\(([^\)]+)\)/gm, '<span style="font-family: monospace; font-weight: bold; border-radius: 5px; color: #ff0000; background-color: #ffffff; padding: 2px;">$2</span>'); // links
}
function format_license(id)
{
	if(id === null)
		return false;

	id.children[1].style.font='initial';
}