this.BX = this.BX || {};
(function (exports) {
	'use strict';

	function _createForOfIteratorHelper(o,allowArrayLike){var it;if(typeof Symbol==="undefined"||o[Symbol.iterator]==null){if(Array.isArray(o)||(it=_unsupportedIterableToArray(o))||allowArrayLike&&o&&typeof o.length==="number"){if(it)o=it;var i=0;var F=function F(){};return {s:F,n:function n(){if(i>=o.length)return {done:true};return {done:false,value:o[i++]}},e:function e(_e){throw _e},f:F}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var normalCompletion=true,didErr=false,err;return {s:function s(){it=o[Symbol.iterator]();},n:function n(){var step=it.next();normalCompletion=step.done;return step},e:function e(_e2){didErr=true;err=_e2;},f:function f(){try{if(!normalCompletion&&it["return"]!=null)it["return"]();}finally{if(didErr)throw err}}}}function _unsupportedIterableToArray(o,minLen){if(!o)return;if(typeof o==="string")return _arrayLikeToArray(o,minLen);var n=Object.prototype.toString.call(o).slice(8,-1);if(n==="Object"&&o.constructor)n=o.constructor.name;if(n==="Map"||n==="Set")return Array.from(o);if(n==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return _arrayLikeToArray(o,minLen)}function _arrayLikeToArray(arr,len){if(len==null||len>arr.length)len=arr.length;for(var i=0,arr2=new Array(len);i<len;i++){arr2[i]=arr[i];}return arr2}function _classCallCheck(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _defineProperties(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass(Constructor,protoProps,staticProps){if(protoProps)_defineProperties(Constructor.prototype,protoProps);if(staticProps)_defineProperties(Constructor,staticProps);return Constructor}/**
	 * ����� ��� ���������� ������� �������������� "�����������"
	 */var Observer=/*#__PURE__*/function(){function Observer(scope){_classCallCheck(this,Observer);this.subscribers=[];this.eventScope=scope||"default";}/**
		 * ����� �������� �� �������
		 */_createClass(Observer,[{key:"subscribe",value:function subscribe(obj){var _iterator=_createForOfIteratorHelper(this.subscribers),_step;try{for(_iterator.s();!(_step=_iterator.n()).done;){var subscriber=_step.value;if(subscriber==obj)return}}catch(err){_iterator.e(err);}finally{_iterator.f();}this.subscribers.push(obj);}/**
		 * ����� ������� �� �������
		 */},{key:"unsubscribe",value:function unsubscribe(obj){this.subscribers=this.subscribers.filter(function(subscriber){return subscriber!==obj});}/**
		 * ���������� � ����� ������� �����������
		 */},{key:"notify",value:function notify(eventType,data){var event=this.getEventHandlerName(eventType);this.subscribers.forEach(function(subscriber){return subscriber[event]?subscriber[event](data):false});}},{key:"getEventHandlerName",value:function getEventHandlerName(eventType){eventType=eventType.charAt(0).toUpperCase()+eventType.slice(1);return "".concat(this.eventScope).concat(eventType,"Event")}}]);return Observer}();

	function asyncGeneratorStep(gen,resolve,reject,_next,_throw,key,arg){try{var info=gen[key](arg);var value=info.value;}catch(error){reject(error);return}if(info.done){resolve(value);}else{Promise.resolve(value).then(_next,_throw);}}function _asyncToGenerator(fn){return function(){var self=this,args=arguments;return new Promise(function(resolve,reject){var gen=fn.apply(self,args);function _next(value){asyncGeneratorStep(gen,resolve,reject,_next,_throw,"next",value);}function _throw(err){asyncGeneratorStep(gen,resolve,reject,_next,_throw,"throw",err);}_next(undefined);})}}function _classCallCheck$1(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _defineProperties$1(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass$1(Constructor,protoProps,staticProps){if(protoProps)_defineProperties$1(Constructor.prototype,protoProps);if(staticProps)_defineProperties$1(Constructor,staticProps);return Constructor}var Request=/*#__PURE__*/function(){function Request(scope){_classCallCheck$1(this,Request);this.scope=scope;}_createClass$1(Request,[{key:"send",value:function(){var _send=_asyncToGenerator(/*#__PURE__*/regeneratorRuntime.mark(function _callee(action){var data,config,defaultConfig,_args=arguments;return regeneratorRuntime.wrap(function _callee$(_context){while(1){switch(_context.prev=_context.next){case 0:data=_args.length>1&&_args[1]!==undefined?_args[1]:{};config=_args.length>2?_args[2]:undefined;defaultConfig={dataType:"json",method:"POST"};config=config||{};config=$.extend(defaultConfig,config);config.url=this.makeApiUrl(action);config.data=config.data||data;_context.next=9;return $.ajax(config);case 9:return _context.abrupt("return",_context.sent);case 10:case"end":return _context.stop();}}},_callee,this)}));function send(_x){return _send.apply(this,arguments)}return send}()},{key:"makeApiUrl",value:function makeApiUrl(action){return "/bitrix/services/main/ajax.php?action=odva:module.api.".concat(this.scope,".").concat(action)}}]);return Request}();

	function _typeof(obj){"@babel/helpers - typeof";if(typeof Symbol==="function"&&typeof Symbol.iterator==="symbol"){_typeof=function _typeof(obj){return typeof obj};}else{_typeof=function _typeof(obj){return obj&&typeof Symbol==="function"&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj};}return _typeof(obj)}function _classCallCheck$2(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _inherits(subClass,superClass){if(typeof superClass!=="function"&&superClass!==null){throw new TypeError("Super expression must either be null or a function")}subClass.prototype=Object.create(superClass&&superClass.prototype,{constructor:{value:subClass,writable:true,configurable:true}});if(superClass)_setPrototypeOf(subClass,superClass);}function _setPrototypeOf(o,p){_setPrototypeOf=Object.setPrototypeOf||function _setPrototypeOf(o,p){o.__proto__=p;return o};return _setPrototypeOf(o,p)}function _createSuper(Derived){var hasNativeReflectConstruct=_isNativeReflectConstruct();return function _createSuperInternal(){var Super=_getPrototypeOf(Derived),result;if(hasNativeReflectConstruct){var NewTarget=_getPrototypeOf(this).constructor;result=Reflect.construct(Super,arguments,NewTarget);}else{result=Super.apply(this,arguments);}return _possibleConstructorReturn(this,result)}}function _possibleConstructorReturn(self,call){if(call&&(_typeof(call)==="object"||typeof call==="function")){return call}return _assertThisInitialized(self)}function _assertThisInitialized(self){if(self===void 0){throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}return self}function _isNativeReflectConstruct(){if(typeof Reflect==="undefined"||!Reflect.construct)return false;if(Reflect.construct.sham)return false;if(typeof Proxy==="function")return true;try{Date.prototype.toString.call(Reflect.construct(Date,[],function(){}));return true}catch(e){return false}}function _getPrototypeOf(o){_getPrototypeOf=Object.setPrototypeOf?Object.getPrototypeOf:function _getPrototypeOf(o){return o.__proto__||Object.getPrototypeOf(o)};return _getPrototypeOf(o)}var Base=/*#__PURE__*/function(_Observer){_inherits(Base,_Observer);var _super=_createSuper(Base);function Base(scope){var _this;_classCallCheck$2(this,Base);_this=_super.call(this,scope);_this.request=new Request(scope);return _this}return Base}(Observer);

	function _typeof$1(obj){"@babel/helpers - typeof";if(typeof Symbol==="function"&&typeof Symbol.iterator==="symbol"){_typeof$1=function _typeof(obj){return typeof obj};}else{_typeof$1=function _typeof(obj){return obj&&typeof Symbol==="function"&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj};}return _typeof$1(obj)}function asyncGeneratorStep$1(gen,resolve,reject,_next,_throw,key,arg){try{var info=gen[key](arg);var value=info.value;}catch(error){reject(error);return}if(info.done){resolve(value);}else{Promise.resolve(value).then(_next,_throw);}}function _asyncToGenerator$1(fn){return function(){var self=this,args=arguments;return new Promise(function(resolve,reject){var gen=fn.apply(self,args);function _next(value){asyncGeneratorStep$1(gen,resolve,reject,_next,_throw,"next",value);}function _throw(err){asyncGeneratorStep$1(gen,resolve,reject,_next,_throw,"throw",err);}_next(undefined);})}}function _classCallCheck$3(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _defineProperties$2(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass$2(Constructor,protoProps,staticProps){if(protoProps)_defineProperties$2(Constructor.prototype,protoProps);if(staticProps)_defineProperties$2(Constructor,staticProps);return Constructor}function _inherits$1(subClass,superClass){if(typeof superClass!=="function"&&superClass!==null){throw new TypeError("Super expression must either be null or a function")}subClass.prototype=Object.create(superClass&&superClass.prototype,{constructor:{value:subClass,writable:true,configurable:true}});if(superClass)_setPrototypeOf$1(subClass,superClass);}function _setPrototypeOf$1(o,p){_setPrototypeOf$1=Object.setPrototypeOf||function _setPrototypeOf(o,p){o.__proto__=p;return o};return _setPrototypeOf$1(o,p)}function _createSuper$1(Derived){var hasNativeReflectConstruct=_isNativeReflectConstruct$1();return function _createSuperInternal(){var Super=_getPrototypeOf$1(Derived),result;if(hasNativeReflectConstruct){var NewTarget=_getPrototypeOf$1(this).constructor;result=Reflect.construct(Super,arguments,NewTarget);}else{result=Super.apply(this,arguments);}return _possibleConstructorReturn$1(this,result)}}function _possibleConstructorReturn$1(self,call){if(call&&(_typeof$1(call)==="object"||typeof call==="function")){return call}return _assertThisInitialized$1(self)}function _assertThisInitialized$1(self){if(self===void 0){throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}return self}function _isNativeReflectConstruct$1(){if(typeof Reflect==="undefined"||!Reflect.construct)return false;if(Reflect.construct.sham)return false;if(typeof Proxy==="function")return true;try{Date.prototype.toString.call(Reflect.construct(Date,[],function(){}));return true}catch(e){return false}}function _getPrototypeOf$1(o){_getPrototypeOf$1=Object.setPrototypeOf?Object.getPrototypeOf:function _getPrototypeOf(o){return o.__proto__||Object.getPrototypeOf(o)};return _getPrototypeOf$1(o)}/**
	 * ����� ��� ������ � ��������
	 */var OdvaBasket=/*#__PURE__*/function(_Base){_inherits$1(OdvaBasket,_Base);var _super=_createSuper$1(OdvaBasket);function OdvaBasket(){_classCallCheck$3(this,OdvaBasket);return _super.apply(this,arguments)}_createClass$2(OdvaBasket,[{key:"getCount",value:/**
		 * ��������� ���������� ������� � �������
		 *
		 * @return {Array} ['PRODUCTS' => number, 'ITEMS' => number]
		 */function(){var _getCount=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee(){var response;return regeneratorRuntime.wrap(function _callee$(_context){while(1){switch(_context.prev=_context.next){case 0:_context.next=2;return this.request.send("getCount",false,{method:"GET"});case 2:response=_context.sent;this.notify("getCount",response);return _context.abrupt("return",response);case 5:case"end":return _context.stop();}}},_callee,this)}));function getCount(){return _getCount.apply(this,arguments)}return getCount}()/**
		 * �������� ���� ������� �� �������
		 */},{key:"clear",value:function(){var _clear=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee2(){var response;return regeneratorRuntime.wrap(function _callee2$(_context2){while(1){switch(_context2.prev=_context2.next){case 0:_context2.next=2;return this.request.send("clear",false,{method:"GET"});case 2:response=_context2.sent;this.notify("clear",response);return _context2.abrupt("return",response);case 5:case"end":return _context2.stop();}}},_callee2,this)}));function clear(){return _clear.apply(this,arguments)}return clear}()/**
		 * ���������� ������ � �������
		 *
		 * @param {number} productId ID ������
		 * @param {number} quantity ���������� ������
		 */},{key:"addItem",value:function(){var _addItem=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee3(productId,quantity){var response;return regeneratorRuntime.wrap(function _callee3$(_context3){while(1){switch(_context3.prev=_context3.next){case 0:_context3.next=2;return this.request.send("addItem",{productId:productId,quantity:quantity});case 2:response=_context3.sent;this.notify("addItem",response);return _context3.abrupt("return",response);case 5:case"end":return _context3.stop();}}},_callee3,this)}));function addItem(_x,_x2){return _addItem.apply(this,arguments)}return addItem}()/**
		 * �������� ������ �� �������
		 *
		 * @param {number} productId ID ������
		 */},{key:"deleteItem",value:function(){var _deleteItem=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee4(productId){var response;return regeneratorRuntime.wrap(function _callee4$(_context4){while(1){switch(_context4.prev=_context4.next){case 0:_context4.next=2;return this.request.send("deleteItem",{productId:productId});case 2:response=_context4.sent;this.notify("deleteItem",response);return _context4.abrupt("return",response);case 5:case"end":return _context4.stop();}}},_callee4,this)}));function deleteItem(_x3){return _deleteItem.apply(this,arguments)}return deleteItem}()/**
		 * ��������� ���������� ������ � �������
		 *
		 * @param {number} productId ID ������
		 * @param {number} quantity ���������� ������ (����� ���� ��� �������������, ��� � �������������)
		 */},{key:"changeItemQuantity",value:function(){var _changeItemQuantity=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee5(productId,quantity){var response;return regeneratorRuntime.wrap(function _callee5$(_context5){while(1){switch(_context5.prev=_context5.next){case 0:_context5.next=2;return this.request.send("changeItemQuantity",{productId:productId,quantity:quantity});case 2:response=_context5.sent;this.notify("changeItemQuantity",response);return _context5.abrupt("return",response);case 5:case"end":return _context5.stop();}}},_callee5,this)}));function changeItemQuantity(_x4,_x5){return _changeItemQuantity.apply(this,arguments)}return changeItemQuantity}()},{key:"applyCoupon",value:function(){var _applyCoupon=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee6(coupon){var response;return regeneratorRuntime.wrap(function _callee6$(_context6){while(1){switch(_context6.prev=_context6.next){case 0:_context6.next=2;return this.request.send("applyCoupon",{coupon:coupon});case 2:response=_context6.sent;this.notify("applyCoupon",response);return _context6.abrupt("return",response);case 5:case"end":return _context6.stop();}}},_callee6,this)}));function applyCoupon(_x6){return _applyCoupon.apply(this,arguments)}return applyCoupon}()},{key:"deleteCoupon",value:function(){var _deleteCoupon=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee7(coupon){var response;return regeneratorRuntime.wrap(function _callee7$(_context7){while(1){switch(_context7.prev=_context7.next){case 0:_context7.next=2;return this.request.send("deleteCoupon",{coupon:coupon});case 2:response=_context7.sent;this.notify("deleteCoupon",response);return _context7.abrupt("return",response);case 5:case"end":return _context7.stop();}}},_callee7,this)}));function deleteCoupon(_x7){return _deleteCoupon.apply(this,arguments)}return deleteCoupon}()},{key:"getInfo",value:function(){var _getInfo=_asyncToGenerator$1(/*#__PURE__*/regeneratorRuntime.mark(function _callee8(){var response;return regeneratorRuntime.wrap(function _callee8$(_context8){while(1){switch(_context8.prev=_context8.next){case 0:_context8.next=2;return this.request.send("getInfo");case 2:response=_context8.sent;this.notify("getInfo",response);return _context8.abrupt("return",response);case 5:case"end":return _context8.stop();}}},_callee8,this)}));function getInfo(){return _getInfo.apply(this,arguments)}return getInfo}()}]);return OdvaBasket}(Base);var basket = new OdvaBasket("basket");

	function _typeof$2(obj){"@babel/helpers - typeof";if(typeof Symbol==="function"&&typeof Symbol.iterator==="symbol"){_typeof$2=function _typeof(obj){return typeof obj};}else{_typeof$2=function _typeof(obj){return obj&&typeof Symbol==="function"&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj};}return _typeof$2(obj)}function asyncGeneratorStep$2(gen,resolve,reject,_next,_throw,key,arg){try{var info=gen[key](arg);var value=info.value;}catch(error){reject(error);return}if(info.done){resolve(value);}else{Promise.resolve(value).then(_next,_throw);}}function _asyncToGenerator$2(fn){return function(){var self=this,args=arguments;return new Promise(function(resolve,reject){var gen=fn.apply(self,args);function _next(value){asyncGeneratorStep$2(gen,resolve,reject,_next,_throw,"next",value);}function _throw(err){asyncGeneratorStep$2(gen,resolve,reject,_next,_throw,"throw",err);}_next(undefined);})}}function _classCallCheck$4(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _defineProperties$3(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass$3(Constructor,protoProps,staticProps){if(protoProps)_defineProperties$3(Constructor.prototype,protoProps);if(staticProps)_defineProperties$3(Constructor,staticProps);return Constructor}function _inherits$2(subClass,superClass){if(typeof superClass!=="function"&&superClass!==null){throw new TypeError("Super expression must either be null or a function")}subClass.prototype=Object.create(superClass&&superClass.prototype,{constructor:{value:subClass,writable:true,configurable:true}});if(superClass)_setPrototypeOf$2(subClass,superClass);}function _setPrototypeOf$2(o,p){_setPrototypeOf$2=Object.setPrototypeOf||function _setPrototypeOf(o,p){o.__proto__=p;return o};return _setPrototypeOf$2(o,p)}function _createSuper$2(Derived){var hasNativeReflectConstruct=_isNativeReflectConstruct$2();return function _createSuperInternal(){var Super=_getPrototypeOf$2(Derived),result;if(hasNativeReflectConstruct){var NewTarget=_getPrototypeOf$2(this).constructor;result=Reflect.construct(Super,arguments,NewTarget);}else{result=Super.apply(this,arguments);}return _possibleConstructorReturn$2(this,result)}}function _possibleConstructorReturn$2(self,call){if(call&&(_typeof$2(call)==="object"||typeof call==="function")){return call}return _assertThisInitialized$2(self)}function _assertThisInitialized$2(self){if(self===void 0){throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}return self}function _isNativeReflectConstruct$2(){if(typeof Reflect==="undefined"||!Reflect.construct)return false;if(Reflect.construct.sham)return false;if(typeof Proxy==="function")return true;try{Date.prototype.toString.call(Reflect.construct(Date,[],function(){}));return true}catch(e){return false}}function _getPrototypeOf$2(o){_getPrototypeOf$2=Object.setPrototypeOf?Object.getPrototypeOf:function _getPrototypeOf(o){return o.__proto__||Object.getPrototypeOf(o)};return _getPrototypeOf$2(o)}var OdvaOrder=/*#__PURE__*/function(_Base){_inherits$2(OdvaOrder,_Base);var _super=_createSuper$2(OdvaOrder);function OdvaOrder(){_classCallCheck$4(this,OdvaOrder);return _super.apply(this,arguments)}_createClass$3(OdvaOrder,[{key:"getBasket",value:function(){var _getBasket=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee(){var response;return regeneratorRuntime.wrap(function _callee$(_context){while(1){switch(_context.prev=_context.next){case 0:_context.next=2;return this.getResponse("getBasket");case 2:response=_context.sent;this.notify("getBasket",response);return _context.abrupt("return",response);case 5:case"end":return _context.stop();}}},_callee,this)}));function getBasket(){return _getBasket.apply(this,arguments)}return getBasket}()},{key:"getUser",value:function(){var _getUser=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee2(){var response;return regeneratorRuntime.wrap(function _callee2$(_context2){while(1){switch(_context2.prev=_context2.next){case 0:_context2.next=2;return this.getResponse("getUser");case 2:response=_context2.sent;this.notify("getUser",response);return _context2.abrupt("return",response);case 5:case"end":return _context2.stop();}}},_callee2,this)}));function getUser(){return _getUser.apply(this,arguments)}return getUser}()},{key:"getDeliveries",value:function(){var _getDeliveries=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee3(){var response;return regeneratorRuntime.wrap(function _callee3$(_context3){while(1){switch(_context3.prev=_context3.next){case 0:_context3.next=2;return this.getResponse("getDeliveries");case 2:response=_context3.sent;this.notify("getDeliveries",response);return _context3.abrupt("return",response);case 5:case"end":return _context3.stop();}}},_callee3,this)}));function getDeliveries(){return _getDeliveries.apply(this,arguments)}return getDeliveries}()},{key:"getPaySystems",value:function(){var _getPaySystems=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee4(){var response;return regeneratorRuntime.wrap(function _callee4$(_context4){while(1){switch(_context4.prev=_context4.next){case 0:_context4.next=2;return this.getResponse("getPaySystems");case 2:response=_context4.sent;this.notify("getPaySystems",response);return _context4.abrupt("return",response);case 5:case"end":return _context4.stop();}}},_callee4,this)}));function getPaySystems(){return _getPaySystems.apply(this,arguments)}return getPaySystems}()},{key:"getUserProfiles",value:function(){var _getUserProfiles=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee5(name,count){var response;return regeneratorRuntime.wrap(function _callee5$(_context5){while(1){switch(_context5.prev=_context5.next){case 0:_context5.next=2;return this.getResponse("getUserProfiles",{name:name,"count":count});case 2:response=_context5.sent;this.notify("getUserProfiles",response);return _context5.abrupt("return",response);case 5:case"end":return _context5.stop();}}},_callee5,this)}));function getUserProfiles(_x,_x2){return _getUserProfiles.apply(this,arguments)}return getUserProfiles}()},{key:"getOrderCalculate",value:function(){var _getOrderCalculate=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee6(deliveryId,paySystemId,cityCode,personTypeId){var response;return regeneratorRuntime.wrap(function _callee6$(_context6){while(1){switch(_context6.prev=_context6.next){case 0:_context6.next=2;return this.getResponse("getOrderCalculate",{deliveryId:deliveryId,paySystemId:paySystemId,cityCode:cityCode,personTypeId:personTypeId});case 2:response=_context6.sent;this.notify("getOrderCalculate",response);return _context6.abrupt("return",response);case 5:case"end":return _context6.stop();}}},_callee6,this)}));function getOrderCalculate(_x3,_x4,_x5,_x6){return _getOrderCalculate.apply(this,arguments)}return getOrderCalculate}()},{key:"getLocationsByName",value:function(){var _getLocationsByName=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee7(name,count){var response;return regeneratorRuntime.wrap(function _callee7$(_context7){while(1){switch(_context7.prev=_context7.next){case 0:_context7.next=2;return this.getResponse("getLocationsByName",{name:name,count:count});case 2:response=_context7.sent;this.notify("getLocationsByName",response);return _context7.abrupt("return",response);case 5:case"end":return _context7.stop();}}},_callee7,this)}));function getLocationsByName(_x7,_x8){return _getLocationsByName.apply(this,arguments)}return getLocationsByName}()},{key:"makeOrder",value:function(){var _makeOrder=_asyncToGenerator$2(/*#__PURE__*/regeneratorRuntime.mark(function _callee8(deliveryId,paySystemId,props,cityCode,personTypeId){var response;return regeneratorRuntime.wrap(function _callee8$(_context8){while(1){switch(_context8.prev=_context8.next){case 0:_context8.next=2;return this.getResponse("makeOrder",{deliveryId:deliveryId,paySystemId:paySystemId,props:props,cityCode:cityCode,personTypeId:personTypeId});case 2:response=_context8.sent;this.notify("makeOrder",response);return _context8.abrupt("return",response);case 5:case"end":return _context8.stop();}}},_callee8,this)}));function makeOrder(_x9,_x10,_x11,_x12,_x13){return _makeOrder.apply(this,arguments)}return makeOrder}()}]);return OdvaOrder}(Base);var order = new OdvaOrder("order");

	function _typeof$3(obj){"@babel/helpers - typeof";if(typeof Symbol==="function"&&typeof Symbol.iterator==="symbol"){_typeof$3=function _typeof(obj){return typeof obj};}else{_typeof$3=function _typeof(obj){return obj&&typeof Symbol==="function"&&obj.constructor===Symbol&&obj!==Symbol.prototype?"symbol":typeof obj};}return _typeof$3(obj)}function asyncGeneratorStep$3(gen,resolve,reject,_next,_throw,key,arg){try{var info=gen[key](arg);var value=info.value;}catch(error){reject(error);return}if(info.done){resolve(value);}else{Promise.resolve(value).then(_next,_throw);}}function _asyncToGenerator$3(fn){return function(){var self=this,args=arguments;return new Promise(function(resolve,reject){var gen=fn.apply(self,args);function _next(value){asyncGeneratorStep$3(gen,resolve,reject,_next,_throw,"next",value);}function _throw(err){asyncGeneratorStep$3(gen,resolve,reject,_next,_throw,"throw",err);}_next(undefined);})}}function _classCallCheck$5(instance,Constructor){if(!(instance instanceof Constructor)){throw new TypeError("Cannot call a class as a function")}}function _defineProperties$4(target,props){for(var i=0;i<props.length;i++){var descriptor=props[i];descriptor.enumerable=descriptor.enumerable||false;descriptor.configurable=true;if("value"in descriptor)descriptor.writable=true;Object.defineProperty(target,descriptor.key,descriptor);}}function _createClass$4(Constructor,protoProps,staticProps){if(protoProps)_defineProperties$4(Constructor.prototype,protoProps);if(staticProps)_defineProperties$4(Constructor,staticProps);return Constructor}function _inherits$3(subClass,superClass){if(typeof superClass!=="function"&&superClass!==null){throw new TypeError("Super expression must either be null or a function")}subClass.prototype=Object.create(superClass&&superClass.prototype,{constructor:{value:subClass,writable:true,configurable:true}});if(superClass)_setPrototypeOf$3(subClass,superClass);}function _setPrototypeOf$3(o,p){_setPrototypeOf$3=Object.setPrototypeOf||function _setPrototypeOf(o,p){o.__proto__=p;return o};return _setPrototypeOf$3(o,p)}function _createSuper$3(Derived){var hasNativeReflectConstruct=_isNativeReflectConstruct$3();return function _createSuperInternal(){var Super=_getPrototypeOf$3(Derived),result;if(hasNativeReflectConstruct){var NewTarget=_getPrototypeOf$3(this).constructor;result=Reflect.construct(Super,arguments,NewTarget);}else{result=Super.apply(this,arguments);}return _possibleConstructorReturn$3(this,result)}}function _possibleConstructorReturn$3(self,call){if(call&&(_typeof$3(call)==="object"||typeof call==="function")){return call}return _assertThisInitialized$3(self)}function _assertThisInitialized$3(self){if(self===void 0){throw new ReferenceError("this hasn't been initialised - super() hasn't been called")}return self}function _isNativeReflectConstruct$3(){if(typeof Reflect==="undefined"||!Reflect.construct)return false;if(Reflect.construct.sham)return false;if(typeof Proxy==="function")return true;try{Date.prototype.toString.call(Reflect.construct(Date,[],function(){}));return true}catch(e){return false}}function _getPrototypeOf$3(o){_getPrototypeOf$3=Object.setPrototypeOf?Object.getPrototypeOf:function _getPrototypeOf(o){return o.__proto__||Object.getPrototypeOf(o)};return _getPrototypeOf$3(o)}var OdvaUser=/*#__PURE__*/function(_Base){_inherits$3(OdvaUser,_Base);var _super=_createSuper$3(OdvaUser);function OdvaUser(){_classCallCheck$5(this,OdvaUser);return _super.apply(this,arguments)}_createClass$4(OdvaUser,[{key:"login",value:function(){var _login2=_asyncToGenerator$3(/*#__PURE__*/regeneratorRuntime.mark(function _callee(_login,password){var remember,response,_args=arguments;return regeneratorRuntime.wrap(function _callee$(_context){while(1){switch(_context.prev=_context.next){case 0:remember=_args.length>2&&_args[2]!==undefined?_args[2]:"N";_context.next=3;return this.request.send("login",{login:_login,password:password,remember:remember},{method:"POST"});case 3:response=_context.sent;this.notify("login",response);return _context.abrupt("return",response);case 6:case"end":return _context.stop();}}},_callee,this)}));function login(_x,_x2){return _login2.apply(this,arguments)}return login}()},{key:"register",value:function(){var _register=_asyncToGenerator$3(/*#__PURE__*/regeneratorRuntime.mark(function _callee2(login,name,lastname,password,confirm,email){var additional,authorize,response,_args2=arguments;return regeneratorRuntime.wrap(function _callee2$(_context2){while(1){switch(_context2.prev=_context2.next){case 0:additional=_args2.length>6&&_args2[6]!==undefined?_args2[6]:[];authorize=_args2.length>7&&_args2[7]!==undefined?_args2[7]:true;_context2.next=4;return this.request.send("register",{login:login,name:name,lastname:lastname,password:password,confirm:confirm,email:email,additional:additional,authorize:authorize},{method:"POST"});case 4:response=_context2.sent;this.notify("register",response);return _context2.abrupt("return",response);case 7:case"end":return _context2.stop();}}},_callee2,this)}));function register(_x3,_x4,_x5,_x6,_x7,_x8){return _register.apply(this,arguments)}return register}()},{key:"ulogin",value:function(){var _ulogin=_asyncToGenerator$3(/*#__PURE__*/regeneratorRuntime.mark(function _callee3(token){var response;return regeneratorRuntime.wrap(function _callee3$(_context3){while(1){switch(_context3.prev=_context3.next){case 0:_context3.next=2;return this.request.send("ulogin",{token:token},{method:"POST"});case 2:response=_context3.sent;this.notify("ulogin",response);return _context3.abrupt("return",response);case 5:case"end":return _context3.stop();}}},_callee3,this)}));function ulogin(_x9){return _ulogin.apply(this,arguments)}return ulogin}()}]);return OdvaUser}(Base);var user = new OdvaUser("user");

	exports.Basket = basket;
	exports.Order = order;
	exports.User = user;

}((this.BX.Odva = this.BX.Odva || {})));
//# sourceMappingURL=main.bundle.js.map
