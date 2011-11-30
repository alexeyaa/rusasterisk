/******************/
//Всплывающее горизонтальное меню
window.addEvent('domready', function() {
	new SmoothScroll({duration:1000}, window);
	//$('menuBg').setStyle('opacity','0.5');
		$$('div.topMenuJS').addEvent('mouseenter', function() {
		var rel = this.getProperty('rel');
		/**************/
		$$('div.topMenuSub').setStyle('opacity',1);
		$$('div.topMenuSub').setStyle('display','none');
		$$('div.topMenuSub')[rel].setStyle('display','block');
		$$('a.topMenuHref')[rel].setStyle('color','black');
		$$('a.topMenuHref')[rel].setStyle('background','silver');
		//new Fx.Tween($$('div.topmenuSub')[rel],{duration:'long'}).start('opacity', 0.5, 1);
		/**************/
	});
	$$('div.topMenuJS').addEvent('mouseleave', function() {
		var rel = this.getProperty('rel');
		/**************/
		$$('div.topMenuSub')[rel].setStyle('display','none');
		$$('a.topMenuHref')[rel].setStyle('color','white');
		$$('a.topMenuHref')[rel].setStyle('background','transparent');
		//new Fx.Tween($$('div.topmenuSub')[rel],{duration:'long'}).start('opacity', 0.5, 0);
		/**************/
	});
	var myTips = new Tips('.filterDrop',{'className':'filterDropTips',showDelay:200});
	if ($('header')) $('header').setStyle('opacity',0.7);
});
/******************/
var myRequestBasket = new Request({method: 'post', url: '/files/bg.basket.php',evalScripts : true,
onSuccess: function(html){
	var myRequestResult = new Element('div',{'id':'myRequestResult','styles':{'padding':'20px'}});
	myRequestResult.innerHTML = html;
	infoBox();
	myRequestResult.inject($('windowFront'));
}
});
var myRequestBasketEasy = new Request({method: 'post', url: '/files/bg.basket.php',evalScripts : true});
var myRequestRegistration = new Request({method: 'post', url: '/files/bg.reg.php',evalScripts : true});
var myRequestAuth = new Request({method: 'post', url: '/files/bg.auth.php',evalScripts : true});
var myRequestExts = new Request({method: 'post', url: '/files/bg.exts.php',evalScripts : true});
/******************/
var infoBox = function () {
	var title = (arguments[0]) ? arguments[0] : '';
	var text = (arguments[1]) ? arguments[1] : '';
	var width = (arguments[2]) ? arguments[2] : 600;
	var height = (arguments[3]) ? arguments[3] : 400;
	//затемнить экран
	var windowBackground = new Element('div',{'id': 'windowBackground','styles': {'opacity':'0','background':'black','position':'absolute','left':'0','top':'0','width':window.getWidth(),'height':window.getScrollHeight(),'z-index':'10000'},
	'events': {
	'click': function(){
			new Fx.Tween(windowBackground,{duration:'long'}).start('opacity', 0.6, 0);
			if (windowBackground.getProperty('opacity')==0) windowBackground.destroy();
			if (windowFront) windowFront.setStyle('display','none');
		}
	}
	});
	windowBackground.inject($('page'));
	new Fx.Tween(windowBackground,{duration:'long'}).start('opacity', 0.4, 0.6);
	//выдать окно
	var lWidth = width;
	var lHeight = height;
	var lCenterWidth = window.getWidth()/2;
	var lCenterHeight = window.getHeight()/2;
	var lLeft = lCenterWidth-lWidth/2;
	var lTop = window.getScrollTop()+lCenterHeight-lHeight/2;
	var windowFront = new Element('div',{'id': 'windowFront','styles': {'opacity':'0','background':'white','position':'absolute','left':lLeft,'top':lTop,'width':lWidth,'height':lHeight+'px','overflow':'auto','z-index':'10001'}});
	var infoBoxClose1 = new Element('div',{'id':'infoBoxClose','styles':{'padding':'1px 10px','background':'#333333','color':'white','text-align':'right','text-transform':'uppercase','font-size':'10px'}});
	infoBoxClose1.innerHTML = "<a href='javascript:void(0)' onclick='infoBoxClose()' style='color:white;font-weight:bold;text-decoration:none;font-size:20px'>X</a>";
	windowFront.inject($('page'));
	new Fx.Tween(windowFront,{duration:'long'}).start('opacity', 0.3, 1);
	//отобразить заголовок
	var windowInn = new Element('div',{'id':'windowInn','styles':{'padding':'20px'}});
	infoBoxClose1.inject(windowFront);
	windowInn.inject(windowFront);
	if (title) {
		var windowTitle = new Element('h1',{'styles':{}});
		windowTitle.innerHTML = title;
		windowTitle.inject(windowInn);
	}

	//отобразить текстовое содержание
	if (text!='') {
		var windowText = new Element('div',{'styles':{}});
		windowText.innerHTML = text;
		windowText.inject(windowInn);
	}
};

function infoBoxClose() {
	new Fx.Tween($('windowBackground'),{duration:'long'}).start('opacity', 0.9, 0);
	var myFunction = function(){
		if ($('windowFront')) $('windowFront').destroy();
		if ($('windowBackground')) $('windowBackground').destroy();
	};
	myFunction.delay(500);
}
/***************************/
//Открытие и скрытие блока
var openDiv = function() {
	var el = $(arguments[0]);
	var status = ($(arguments[0]).getStyle('display')=='none') ? 'block' : 'none';

	if (el.getProperty('class')=='thisBlock') $$('div.thisBlock').setStyle('display','none');
	
	el.setStyle('opacity',0);
	el.setStyle('display',status);
	new Fx.Tween(el,{duration:'long'}).start('opacity', 0.5, 1);
}

//Вывод системных сообщений
var messageItem = function() {
	var el = $(arguments[0]);
	var mess = arguments[1];
	var color = (arguments[2]) ? arguments[2] : 'red';
	var size = (arguments[3]) ? arguments[3] : '13';
	el.setStyle('opacity',0);
	el.setStyle('font-size','13px');
	el.set('html',mess);
	new Fx.Tween(el,{duration:'long'}).start('opacity', 0.5, 1);
	new Fx.Tween(el,{duration:'long'}).start('font-size', size);
	$(el).setStyle('background','#eaeaea');
	new Fx.Morph(el,{duration:'long'}).set({'color': color});
}

//Загрузка капчи
function myUgadai() {
	var tmp = $('myImage').getProperty('rel')+1;
	$('myImage').setProperty('rel',tmp);
	img = new Image();
	img.src = '/imca/ugadai.png?'+tmp;
	$('myImage').src = img.src;
	 $('ImgText').focus();
}

//Отображение дополнительной информации метода оплаты / доставки
var deliveryInfo = function() {
	//alert(1);
	var class1 = arguments[0];
	var id1 = arguments[1];
	var var1 = (arguments[2]) ? arguments[2] : 0;
	$$('div.'+class1).setStyle('display','none');
	$$('div.'+class1).setStyle('opacity','0');
	var el = $(class1+'_'+id1);
	el.setStyle('display','block');
	new Fx.Tween(el,{duration:'long'}).start('opacity', 0.5, 1);
	//Посчет увеличения стоимости
	if (var1==1) myRequestBasketEasy.send('action=priceMore&variant='+id1);
};

//Очистка предыдущего выбора при переключении способов оплаты / доставки
var deliveryCheckEmpty = function() {
	var radios1 = $$('input.'+arguments[0]);
	var descrs1 = $$('div.'+arguments[1]);
	var divrel  = (arguments[2]) ? arguments[2] : "";
	descrs1.setStyle('display','none');
	for (var key in radios1) {
		if (radios1[key].checked==true) {
			radios1[key].checked = false;
		}
	}
	//Выделение первого вложенного элемента
	if (divrel!='') {
		var t = arguments[1].replace('Toggle','');
		var tt = $$('div.'+t);
		for (var i=0;  i<=1000; i++) {
			if (!tt[i]) break;
			if (tt[i].getStyle('display')=='block' && tt[i].getProperty('rel')==divrel) {
				radios1[i].checked=true;
				deliveryInfo('mBlockItemsPaymentToggle',divrel+'_'+radios1[i].value,1)
				break;
			}
  		}
	}
}

//Физическое лицо / Юридическое лицо - отображение соответствующих способов оплаты
var clientStatus = function() {
	var val1 = arguments[0];
	if (val1==1) {
		$$('div.mBlockItemsPayment').setStyle('display','none');
		$$('div.mBlockItemsPayment_1').setStyle('display','block');
	} else {
		$$('div.mBlockItemsPayment').setStyle('display','block');
		$$('div.mBlockItemsPayment_1').setStyle('display','none');
		
	}
	
	myRequestRegistration.send('reg=clientCompany&company='+val1);
	$('companyRad_'+val1).checked=true;
	$('finalInfo').set('html','');
	deliveryCheckEmpty('deliveryRadios','mBlockItemsToggle');
	deliveryCheckEmpty('paymentRadios','mBlockItemsPaymentToggle')
}