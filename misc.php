<?php

error_reporting(0);

?>
<script type="text/javascript">

 function visibility(aff)
 {

 
	switch (aff) {
	              case ('Y') :     document.getElementById('DivRes').style.display='';
								   document.getElementById('link_more').style.display='none';
								   break;

				}
}

	
	
	
/**********************
*	LightBox Code from xul.fr
***********************/	


function viewHeight() 
{
    if(window.innerHeight)return(window.innerHeight);
    if(document.documentElement && document.documentElement.clientHeight) 
         return(document.documentElement.clientHeight);
    if(document.body) return(document.body.clientHeight); 
    return 50;
}
	

function gradient(id, level)
{
	var box = document.getElementById(id);
	box.style.opacity = level;
	box.style.MozOpacity = level;
	box.style.KhtmlOpacity = level;
	box.style.filter = "alpha(opacity=" + level * 100 + ")";
	box.style.display="block";
	return;
}


function fadein(id) 
{
	var level = 0;
	while(level <= 1)
	{
		setTimeout( "gradient('" + id + "'," + level + ")", (level* 1000) + 10);
		level += 0.01;
	}
}


// Open the lightbox


function openbox( fadin)
{
  var box = document.getElementById('box'); 
  var filter =  document.getElementById('filter');	
  document.getElementById('filter').style.display='block';

  if(fadin)
  {
	 
	 if(navigator.appName.substring(0, 3) == "Mic")  // for IE
	  {
		box.style.display='block';
		x = document.documentElement.scrollTop + document.body.scrollTop + 
		box.offsetHeight / 4;
		box.style.top = x + "px";
		filter.style.top = document.documentElement.scrollTop + document.body.scrollTop;
	  }
	  else
	  {
		box.style.display='block';
		var top =  (viewHeight() - box.offsetHeight ) / 2;    
		box.style.top = top + 'px';
		box.style.position='fixed'; // fixed does not work on IE
		filter.style.position='fixed'; 
	  }
	gradient("box", 0);
	fadein("box");

  }
  else
  { 	
    box.style.display='block';
  }
  
}


// Close the lightbox

function closebox()
{
   document.getElementById('box').style.display='none';
   document.getElementById('filter').style.display='none';
}


/*====================================================
	- HTML Table Filter Generator v1.1
	- développé par Max Guglielmi
	- mguglielmi.free.fr/scripts/TableFilter/?l=fr
	- Prière de conserver ce message
=====================================================*/

var TblId, StartRow, SearchFlt;
TblId = new Array, StartRow = new Array;


function setFilterGrid(id)
/*====================================================
	- vérifie que l'id passé en param existe bien et
	que c'est bein une table
	- vérifie la présence d'autres paramètres
	- appel de la fonction qui ajoute les inputs et
	le bouton
=====================================================*/
{
	var tbl = document.getElementById(id);
	var ref_row, fObj;
	if(tbl != null && tbl.nodeName.toLowerCase() == "table")
	{
		TblId.push(id);
		if(arguments.length>1)
		{
			for(var i=0; i<arguments.length; i++)
			{
				var argtype = typeof arguments[i];
				
				switch(argtype.toLowerCase()){
					case "number":
						ref_row = arguments[i];
					break;
					case "object":
						fObj = arguments[i];
					break;
				}//switch
							
			}//for
		}//if
		
		ref_row == undefined ? StartRow.push(2) : StartRow.push(ref_row+2);
		var ncells = getCellsNb(id,ref_row);
		AddRow(id,ncells,fObj);
	}
}

function AddRow(id,n,f)
/*====================================================
	- ajoute un filtre (input ou select) pour chaque 
	colonne
	- ajoute le bouton dans la dernière colonne
=====================================================*/
{
	
	var t = document.getElementById(id);
	var fltrow = t.insertRow(0);
	var inpclass;
	for(var i=0; i<n; i++)
	{
		var fltcell = fltrow.insertCell(i);
		i==n-1 ? inpclass = "flt_s" : inpclass = "flt";
		
		if(f==undefined || f["col_"+i]==undefined || f["col_"+i]=="none") 
		{
			var inp = document.createElement("input");		
			inp.setAttribute("id","flt"+i+"_"+id);
			if(f==undefined || f["col_"+i]==undefined) inp.setAttribute("type","text");
			else inp.setAttribute("type","hidden");
			//inp.setAttribute("class","flt"); //ne marche pas sur ie<=6		
			fltcell.appendChild(inp);
			document.getElementById("flt"+i+"_"+id).className = inpclass;
			document.getElementById("flt"+i+"_"+id).onkeypress = DetectKey;			
		}
		else if(f["col_"+i]=="select")
		{
			var slc = document.createElement("select");
			slc.setAttribute("id","flt"+i+"_"+id);
			slc.setAttribute('onChange',"Filter('lightbox_tab')");
			fltcell.appendChild(slc);
			PopulateOptions(id,i,n);
			document.getElementById("flt"+i+"_"+id).className = inpclass;
			document.getElementById("flt"+i+"_"+id).onkeypress = DetectKey;
		}
		

		if(i==n-1) // ajout du bouton 
		{
			var btn = document.createElement("a");
			
			btn.setAttribute("id","btn"+i+"_"+id);
			btn.setAttribute("href","javascript:Filter('"+id+"');");
			btn.setAttribute("class","go");
			fltcell.appendChild(btn);
			btn.appendChild(document.createTextNode("go"));
			
			document.getElementById("btn"+i+"_"+id).className = "btn";
		}//if		
		
	}// for i
}




function PopulateOptions(id,cellIndex,ncells)
/*====================================================
	- ajoute les option au select
	- ne rajoute qu'une seule instance d'une valeur
=====================================================*/
{
	var t = document.getElementById(id);
	var start_row = getStartRow(id);
	var row = t.getElementsByTagName("tr");
	var OptArray = new Array;
	var optIndex = 0; // option index
	

	//Add an "all" option
	var all = "<?php _e("All","TeamResults"); ?>";
	OptArray.push(all);
	var currOpt = new Option(all,"",false,false);
	document.getElementById("flt"+cellIndex+"_"+id).options[optIndex] = currOpt;
	
	for(var k=start_row; k<row.length; k++)
	{
		var cell = getChildElms(row[k]).childNodes;
		var nchilds = cell.length;
		
		if(nchilds == ncells){// checks if row has exact cell #
			
			for(var j=0; j<nchilds; j++)// this loop retrieves cell data
			{
				if(cellIndex==j)
				{
					var cell_data = getCellText(cell[j]);
					if(OptArray.toString().search(cell_data) == -1)
					// checks if celldata is already in array
					{
						optIndex++;
						OptArray.push(cell_data);
						var currOpt = new Option(cell_data,cell_data,false,false);
						document.getElementById("flt"+cellIndex+"_"+id).options[optIndex] = currOpt;
					}
				}//if cellIndex==j
			}//for j
			
		}//if
		
	}//for k
}

function Filter(id)
/*====================================================
	- récupère les chaines recherchés dans le array 
	SearchFlt
	- récupère le contenu des td de chaque tr et 
	le compare à la chaine recherché dans la colonne
	courante
	- le tr est caché si toutes les chaines ne sont 
	pas trouvé
=====================================================*/
{	
	getFilters(id);
	var t = document.getElementById(id);
	var SearchArgs = new Array();
	var ncells = getCellsNb(id);
	
	for(i in SearchFlt) SearchArgs.push((document.getElementById(SearchFlt[i]).value).toLowerCase());
	
	var start_row = getStartRow(id);
	var row = t.getElementsByTagName("tr");
	
	for(var k=start_row; k<row.length; k++)
	{	
		/*** si la table a été déjà filtré certaines lignes ne sont pas visibles ***/
		if(row[k].style.display == "none") row[k].style.display = "";
		
		var cell = getChildElms(row[k]).childNodes;
		var nchilds = cell.length;

		if(nchilds == ncells){// vérife que la ligne a le nombre exact de cellules
			var cell_value = new Array();
			var occurence = new Array();
			var isRowValid = true;
				
			for(var j=0; j<nchilds; j++)// cette boucle récupère le contenu de la cellule
			{
				var cell_data = getCellText(cell[j]).toLowerCase();
				cell_value.push(cell_data);
				
				if(SearchArgs[j]!="")
				{
					occurence[j] = cell_data.split(SearchArgs[j]).length;
				}
			}//for j
			
			for(var t=0; t<ncells; t++)
			{
				if(SearchArgs[t]!="" && occurence[t]<2) 
				{
					isRowValid = false;					
				}
			}//for t
			
		}//if				
		
		if(isRowValid==false) row[k].style.display = "none";
		else row[k].style.display = "";
		
	}// for k
}

function getCellsNb(id,nrow)
/*====================================================
	- renvoie le nombre de cellules d'une ligne
	- si nrow est passé en paramètre, renvoie le 
	nombre de cellules de la ligne specifiée
=====================================================*/
{
  	var t = document.getElementById(id);
	var tr;
	if(nrow == undefined) tr = t.getElementsByTagName("tr")[0];
	else  tr = t.getElementsByTagName("tr")[nrow];
	var n = getChildElms(tr);
	return n.childNodes.length;
}

function getFilters(id)
/*====================================================
	- les id des filtres (input) sont gardés dans le
	array SearchFlt
=====================================================*/
{
	SearchFlt = new Array;
	var t = document.getElementById(id);
	var tr = t.getElementsByTagName("tr")[0];
	var enfants = tr.childNodes;
	
	for(var i=0; i<enfants.length; i++) SearchFlt.push(enfants[i].firstChild.getAttribute("id"));
}

function getStartRow(id)
/*====================================================
	- renvoie la ligne de réference d'un tableau
=====================================================*/
{
	var r;
	for(j in TblId)
	{
		if(TblId[j] == id) r = StartRow[j];
	}
	return r;
}

function getChildElms(n)
/*====================================================
	- vérifie que le noeud est bien un 
	(ELEMENT_NODE nodeType=1)
	- Enlève les éléments texte 
	(TEXT_NODE nodeType=3)
	- Expres pour firefox qui renvoi tous le childs
	d'un noeud (ELEMENT_NODE+TEXT_NODE+les autres)
=====================================================*/
{
	if(n.nodeType == 1)
	{
		var enfants = n.childNodes;
		for(var i=0; i<enfants.length; i++)
		{
			var child = enfants[i];
			if(child.nodeType == 3) n.removeChild(child);
		}
		return n;	
	}
}

function getCellText(n)
/*====================================================
	- renvoie le texte du noeud et de ses childs
	- au cas où on a des balises dans le td, on 
	récupère quand même leur contenu pour que la 
	recherche ne soit pas faussée
=====================================================*/
{
	var s = "";
	var enfants = n.childNodes;
	for(var i=0; i<enfants.length; i++)
	{
		var child = enfants[i];
		if(child.nodeType == 3) s+= child.data;
		else s+= getCellText(child);
	}
	return s;
}

function DetectKey(e)
{
/*====================================================
	- fonction de detection de la touche 'enter' 
	attaché	un élément défini (l'attribut onkeypress
	dans les inputs)
=====================================================*/
	var evt=(e)?e:(window.event)?window.event:null;
	if(evt){
		var key=(evt.charCode)?evt.charCode:
			((evt.keyCode)?evt.keyCode:((evt.which)?evt.which:0));
		if(key=="13")
		{
			var tblid = this.getAttribute("id").split("_")[1];
			Filter(tblid);
		}
	}	
}

	
</script>

<style type="text/css">
       
	   
	#team_results ul li  
	{
		font-size: 11px;
	}
	
	label
	{
		font-size: 11px;
	}
	
	.score
	{
		font-weight: bold;
	}
	
/**********************
*	LightBox CSS
***********************/	

	#filter
	{
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: #000;
		z-index:10;
		opacity:0.5;
		filter: alpha(opacity=50);
	}


	#box 
	{
		display: none;
		position: absolute;
		top: 20%;
		left: 20%;
		width: 900px;
		height: 400px;
		padding: 48px;
		margin:0;
		border: 1px solid black;
		background-color: white;
		z-index:101;
		overflow-x: hidden; overflow-y: scroll; 
	}

	#boxtitle
	{
		position:absolute;
		float:center;
		top:0;
		left:0;
		width:496px;
		height:24px;
		padding:0;
		padding-top:14px;
		padding-left: 30px;
		left-padding:8px;
		margin:0;
		/*border-bottom:4px solid #eee;
		background-color: #ddd;
		color:black;
		text-align:center;*/
	}
	
	.lightbox 
	{
		width: 750px;
		border: 1px solid black;
		font-size: 11px;
		margin-left:15px;
		padding-bottom: 10px;
		cellspacing: 10px;
	}
		.lightbox th
	{
		background-color: #999999;
		border: 1px solid black;
		color:#333333;
	}
	
	.lightbox tr
	{
		height: 30px;
	}
	
	
	
	caption{ margin:10px 0 0 5px; padding:10px; text-align:left; }
th{ background-color:#003366; color:#FFFFFF; padding:2px; border:1px solid #ccc; }

/*====================================================
	- classes des filtres
	- éditer les classes ci-dessous pour changer le
	style des <input> et du bouton "go" <a>
=====================================================*/
a.btn{
	border:1px outset #ccc;
	margin:1px; padding:1px;
	text-decoration:none; color: #666;
	background-color:#CCCCCC;
}
.flt{ 
	background-color:#f4f4f4; border:1px inset #ccc; 
	margin:0; width:100%;
}
.flt_s{
	background-color:#f4f4f4; border:1px inset #ccc; 
	margin:0; width:90%;
}
	



 
 </style>


