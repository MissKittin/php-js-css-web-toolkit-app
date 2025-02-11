document.addEventListener('DOMContentLoaded', function(){
	var startTime=performance.now();

	function format_readme(input, output)
	{
		// this function is created only for toolkit readme

		if(output === null)
			return false;

		var html='';
		var code_block_opened=false;
		var ul_list_opened=false;
		var ul_2_list_opened=false;
		var ul_3_list_opened=false;
		var ol_list_opened=false;
		var markdown=input.innerHTML
		.	replace('`SPDX-License-Identifier: MIT OR GPL-2.0-only`', '')
		.	replace(/\n\n\t\t\t([\s\S]*?)\n\n/g, function(match){
				// code block embedded in list

				return ''
				+	'<br><br><div class="md_inline_code_block">'
				+		match
				.		trim()
				.		replace(/\n/g, '<br>')
				.		replace(/\t\t\t/g, '')
				+	'</div><br>\n';
			})
		.	replace(/\n\n\t\t([\s\S]*?)\n\n/g, function(match){
				// code block embedded in list

				if(match.trimStart().substring(0, 1) === '#') // do not touch server configurations
					return match;

				return ''
				+	'<br><br><div class="md_inline_code_block">'
				+		match
				.		trim()
				.		replace(/\n/g, '<br>')
				.		replace(/\t\t/g, '')
				+	'</div><br>\n';
			})
		.	split('\n');

		for(var i=0; i<markdown.length; i++)
		{
			// headers
			if(markdown[i].substring(0, 4) === '####')
				html+='<h4 id="'+markdown[i]
				.	substring(4)
				.	trim()
				.	toLowerCase()
				.	replace(/\W/g, '-')
				+	'">'
				+	markdown[i]
				.	slice(5)
				+	'</h4>';
			else if(markdown[i].substring(0, 3) === '###')
				html+='<h3 id="'+markdown[i]
				.	substring(3)
				.	trim()
				.	toLowerCase()
				.	replace(/\.|'/g, '') // Hint - index.php | Minifying assets - matthiasmullie's minifier
				.	replace(/\W/g, '-')
				.	replace(/--$|-$/g, '') // Creating database configuration for pdo_connect()
				.	replace(/----/g, '-') // Seeding database offline with pdo_connect()
				+	'">'
				+	markdown[i]
				.	slice(4)
				+	'</h3>';
			else if(markdown[i].substring(0, 2) === '##')
				html+='<h2 id="'+markdown[i]
				.	substring(2)
				.	trim()
				.	toLowerCase()
				.	replace(/\W/g, '-')
				+	'">'
				+	markdown[i]
				.	slice(3)
				+	'</h2>';
			else if(
				(!code_block_opened) &&
				(markdown[i].substring(0, 1) === '#')
			)
				html+='<h1 id="'+markdown[i]
				.	substring(1)
				.	trim()
				.	toLowerCase()
				.	replace(/\W/g, '-')
				+	'">'
				+	markdown[i]
				.	slice(2)
				+	'</h1>';

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
					html+='<div class="md_code_block">';
					code_block_opened=true;
				}
			}

			// lists opening
			else if(
				(!isNaN(markdown[i].substring(0, 1))) &&
				(
					(markdown[i].substring(1, 3) === ') ') ||
					(markdown[i].substring(2, 4) === '. ')
				)
			){
				if(!ol_list_opened)
				{
					html+='<ol>';
					ol_list_opened=true;
				}

				html+='<li>'
				+	markdown[i]
				.	slice(3)
				.	replace('  ', '<br>')
				.	replace(/`(.*?)`/g, '<span class="md_inline_code_block">$1</span>') // inline code blocks
				+	'</li>';
			}
			else if(markdown[i].substring(0, 2) === '* ')
			{
				if(ul_2_list_opened)
				{
					html+='</ul>';
					ul_2_list_opened=false;
				}

				if(!ul_list_opened)
				{
					html+='<ul>';
					ul_list_opened=true;
				}

				html+='<li>'
				+	markdown[i]
				.	slice(2)
				.	replace('  ', '<br>')
				.	replace(/`(.*?)`/g, '<span class="md_inline_code_block">$1</span>') // inline code blocks
				+	'</li>';
			}

			// regular text/lists closing/code blocks linebreaks
			else
			{
				if(markdown[i].substring(0, 4) === "\t\t* ")
				{
					if(!ul_3_list_opened)
					{
						html+='<ul>';
						ul_3_list_opened=true;
					}

					html+='<li>'
					+	markdown[i]
					.	slice(3)
					+	'</li>';
				}
				if(markdown[i].substring(0, 3) === "\t* ")
				{
					if(ul_3_list_opened)
					{
						html+='</ul>';
						ul_3_list_opened=false;
					}

					if(!ul_2_list_opened)
					{
						html+='<ul>';
						ul_2_list_opened=true;
					}

					html+='<li>'
					+	markdown[i]
					.	slice(2)
					.	replace('  ', '<br>')
					.	replace(/`(.*?)`/g, '<span class="md_inline_code_block">$1</span>') // inline code blocks
					+	'</li>';
				}
				else if(markdown[i].substring(0, 1) !== "\t")
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
					html+=markdown[i]+'<br>';
				else if(!ul_2_list_opened)
					html+=markdown[i]
					.	trimStart()
					.	replace('  ', '<br>')
					.	replace(/`(.*?)`/g, '<span class="md_inline_code_block">$1</span>') // inline code blocks
					.	replace(/\*\*(.*?)\*\*/gm, '<strong>$1</strong>') // bolds
					.	replace('*nix', '&#42;nix').replace(/\*(.*?)\*/gm, '<i>$1</i>') // italians
					.	replace(':)', '&#128512;');
			}
		}

		// links
		output.innerHTML=html
		.	replace(/\[([^\]]+)\]\(#([^\)]+)\)/gm, '<span class="md_link"><a href="#$2">$1</a></span>') // headlink hash
		.	replace(/\[([^\]]+)\]\(([^\)]+)\)/gm, function(match, p1, p2){
				if(match === '[HOWTO](HOWTO.md)')
					return '<span class="md_link"><a href="#howto">'+p2.slice(0, -3)+'</a></span>';

				return '<span class="md_link">'+p2+'</span>';
			});
	}

	var markdowns=document.getElementsByClassName('markdown');

	for(var i=0; i<markdowns.length; i++)
		format_readme(
			markdowns[i].children[1],
			markdowns[i]
		);

	document.getElementById('page_content').style.display='block';
	document.getElementById('loading_page_content').style.display='none';

	var endTime=performance.now();
	console.log(`Formatting the document took ${endTime-startTime} milliseconds`);
}, false);