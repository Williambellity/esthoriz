/** * Nécessite jQuery UI pour fonctionner * Avec pluging Slider et effet DROP */var lock = 0;function unlock() {	lock = 0;}$(document).ready(function() {	var box = 1;	function getIdRI(n) {		id = "#r_info_"+n;		return id;	}	function getIdBox(n) {		id = "#info_"+n;		return id;	}	
	function changeBox(n1,n2) {		$(getIdRI(n1)).toggleClass("selected",1000);		$(getIdRI(n2)).toggleClass("selected",1000);			if(n1>n2) {			dir1 = "right";			dir2 = "left";		} else {			dir1 = "left";			dir2 = "right";		}				$(getIdBox(n1)).hide("drop",{direction: dir1}, 500, function() {			$(getIdBox(n2)).show("drop",{direction: dir2}, 500);		});			box = n2;		setTimeout('unlock()', 600);
	}

	function nextBox() {		if(lock==0) {			lock=1;			n1 = box;						if(n1==maxBox) {				n2 = 1;			} else {				n2 = n1+1;			}						changeBox(n1,n2);		}
	}

	function lastBox() {		if(lock==0) {			lock=1;			n1 = box;						if(n1==1) {				n2 = maxBox;			} else {				n2 = n1-1;			}						changeBox(n1,n2);		}
	}

	function onClickRI(object) {		if(lock==0) {			n2 = parseInt($(object).attr("id").substring(7,8));			n1 = box;						if(n1!=n2) {				lock=1;				changeBox(n1,n2);				return true;			} else {				return false;			}		}
	}	$(".reduc_info").hover(		function () {			$(this).addClass("hover_part_info");		},		function () {			$(this).removeClass("hover_part_info");		}	);	$("#b_last").hover(		function () {			$(this).attr("src","/apps/participer/front/images/fleche_left_hover.png");		},		function () {			$(this).attr("src","/apps/participer/front/images/fleche_left.png");		}	);	$("#b_next").hover(		function () {			$(this).attr("src","/apps/participer/front/images/fleche_right_hover.png");		},		function () {			$(this).attr("src","/apps/participer/front/images/fleche_right.png");		}	);	$(".reduc_info").click( function() {onClickRI(this);});	$("#b_last").click( function() {lastBox();});	$("#b_next").click( function() {nextBox();});});