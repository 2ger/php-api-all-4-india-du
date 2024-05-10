(self["webpackChunkvue_antd_pro"]=self["webpackChunkvue_antd_pro"]||[]).push([[606],{10606:function(t,e,r){"use strict";r.r(e),r.d(e,{default:function(){return c}});r(68309);var n=function(){var t=this,e=t._self._c;return e("page-header-wrapper",[e("a-form",{ref:"addUserform",staticClass:"form",attrs:{form:t.addUserform}},[e("a-card",{staticClass:"card",attrs:{bordered:!1,loading:t.loading}},[e("a-row",{staticClass:"form-row",attrs:{gutter:48}},[e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"选择用户"}},[e("a-input-search",{directives:[{name:"decorator",rawName:"v-decorator",value:["userId",{rules:[{required:!0,message:"请输入用户ID"}]}],expression:"['userId', { rules: [{ required: true, message: '请输入用户ID', }] }]"}],attrs:{placeholder:"输入用户id查询用户信息","enter-button":""},on:{search:t.getUsersearch}})],1)],1)],1),e("a-row",{staticClass:"form-row",attrs:{gutter:48}},[e("a-col",{attrs:{md:24,lg:24,sm:24}},[e("div",[e("a-descriptions",{attrs:{bordered:"",column:{xxl:4,xl:3,lg:3,md:3,sm:2,xs:1}}},[e("a-descriptions-item",{attrs:{label:"用户名称："}},[t._v(" "+t._s(t.userInfo.realName)+" ")]),e("a-descriptions-item",{attrs:{label:"所属代理："}},[t._v(" "+t._s(t.userInfo.agentName)+" ")]),e("a-descriptions-item",{attrs:{label:"账号类型："}},[t._v(" "+t._s(1==t.userInfo.accountType?"模拟用户":0==t.userInfo.accountType?"实盘用户":"")+" ")]),e("a-descriptions-item",{attrs:{label:"手机号码："}},[t._v(" "+t._s(t.userInfo.phone)+" ")]),e("a-descriptions-item",{attrs:{label:"总资金："}},[t._v(" "+t._s(t.userInfo.userAmt)+" ")]),e("a-descriptions-item",{attrs:{label:"可用资金："}},[t._v(" "+t._s(t.userInfo.enableAmt)+" ")])],1)],1)])],1),e("a-row",{staticClass:"form-row",staticStyle:{"margin-top":"20px"},attrs:{gutter:48}},[e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"选择股票"}},[e("a-input-search",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockCode",{rules:[{required:!0,message:"输入股票代码查询股票信息"}]}],expression:"['stockCode', { rules: [{ required: true, message: '输入股票代码查询股票信息', }] }]"}],attrs:{placeholder:"输入股票代码查询股票信息","enter-button":""},on:{search:t.getstockdetail}})],1)],1)],1),e("a-row",{staticClass:"form-row",attrs:{gutter:48}},[e("a-col",{attrs:{md:24,lg:24,sm:24}},[e("div",[e("a-descriptions",{attrs:{bordered:"",column:{xxl:4,xl:3,lg:3,md:3,sm:2,xs:1}}},[e("a-descriptions-item",{attrs:{label:"股票名字："}},[t._v(" "+t._s(t.stockInfo.name)+" ")]),e("a-descriptions-item",{attrs:{label:"股票代码："}},[t._v(" "+t._s(t.stockInfo.code)+" ")]),e("a-descriptions-item",{attrs:{label:"股票现价："}},[e("span",{class:t.stockInfo.hcrate<0?"greens":"reds"},[t._v(t._s(t.stockInfo.nowPrice))])]),e("a-descriptions-item",{attrs:{label:"涨跌："}},[e("span",{class:t.stockInfo.hcrate<0?"greens":"reds"},[t._v(t._s(t.stockInfo.hcrate))])])],1)],1)])],1),e("a-row",{staticClass:"form-row",staticStyle:{"margin-top":"20px"},attrs:{gutter:48}},[e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"买入时间"}},[e("a-date-picker",{directives:[{name:"decorator",rawName:"v-decorator",value:["buyTime",{rules:[{required:!0,message:"请填写买入时间"}]}],expression:"['buyTime', { rules: [{ required: true, message: '请填写买入时间', }] }]"}],staticStyle:{width:"100%"},attrs:{"show-time":"",format:"YYYY-MM-DD HH:mm:ss"},on:{change:t.onChangeRangeDate}})],1)],1),e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"买入价格"}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["buyPrice",{rules:[{required:!0,message:"请填写买入价格"}]}],expression:"['buyPrice', { rules: [{ required: true, message: '请填写买入价格', }] }]"}],attrs:{placeholder:"输入所选择时间点对应的价格"}})],1)],1),e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"杠杆倍数"}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["lever",{rules:[{required:!0,message:"输入买入杠杆倍数"}]}],expression:"['lever', { rules: [{ required: true, message: '输入买入杠杆倍数', }] }]"}],attrs:{placeholder:"输入买入杠杆倍数"}})],1)],1),e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"买入方向"}},[e("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["buyType",{rules:[{required:!0,message:"请选择买入方向"}]}],expression:"['buyType', { rules: [{ required: true, message: '请选择买入方向', }] }]"}],attrs:{placeholder:"请选择买入方向"}},[e("a-select-option",{attrs:{value:0}},[t._v("买涨")]),e("a-select-option",{attrs:{value:1}},[t._v("买跌")])],1)],1)],1)],1),e("a-row",{staticClass:"form-row",staticStyle:{"margin-top":"20px"},attrs:{gutter:48}},[e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",{attrs:{label:"买入数量"}},[e("a-input-number",{directives:[{name:"decorator",rawName:"v-decorator",value:["buyNum",{rules:[{required:!0,message:"请输入买入数量"}]}],expression:"['buyNum', { rules: [{ required: true, message: '请输入买入数量', }] }]"}],staticStyle:{width:"100%"},attrs:{placeholder:"请输入买入数量",min:t.details.buyMinNum,max:t.details.buyMaxNum}})],1)],1)],1)],1)],1),e("div",{staticClass:"bottomfixed"},[e("div",{staticStyle:{float:"right"}},[e("a-button",{attrs:{type:"primary",loading:t.addUserDialogloading},on:{click:t.OkaddUserdialog}},[t._v(" 保存当前设置 ")])],1)])],1)},i=[],o=r(65903),a=r(50171),s=(r(25030),{name:"Sharessetting",data:function(){return{addUserform:this.$form.createForm(this),loading:!1,labelCol:{xs:{span:10},sm:{span:10},md:{span:7}},wrapperCol:{xs:{span:14},sm:{span:14},md:{span:16}},addUserDialogloading:!1,details:{},userInfo:{},stockInfo:{}}},mounted:function(){this.getdetail()},methods:{getdetail:function(){var t=this,e=this;this.loading=!0,(0,o.Wl)().then((function(r){t.details=r.data,setTimeout((function(){e.loading=!1}),500)}))},getUsersearch:function(){var t=this;(0,a.pc)({userId:this.addUserform.getFieldValue("userId")}).then((function(e){0===e.status?e.data?t.userInfo=e.data:(t.$message.error({content:"没有该用户!"}),t.userInfo=""):(t.$message.error({content:e.msg}),t.userInfo="")}))},getstockdetail:function(){var t=this;(0,a.Ns)({code:this.addUserform.getFieldValue("stockCode")}).then((function(e){0===e.status?t.stockInfo=e.data.stock:(t.$message.error({content:e.msg}),t.stockInfo="")}))},onChangeRangeDate:function(t,e){this.buyTime=e},OkaddUserdialog:function(){var t=this,e=this.$refs.addUserform.form;e.validateFields((function(e,r){e||(t.addUserDialogloading=!0,r.buyTime=t.buyTime,(0,a.Sy)(r).then((function(e){0==e.status?(t.$message.success({content:"生成模拟持仓成功",duration:2}),t.getdetail()):t.$message.error({content:e.msg}),t.addUserDialogloading=!1})))}))}}}),u=s,d=r(70713),l=(0,d.Z)(u,n,i,!1,null,"dd19aea6",null),c=l.exports},50171:function(t,e,r){"use strict";r.d(e,{A6:function(){return p},AI:function(){return h},AP:function(){return u},Ap:function(){return g},EO:function(){return b},F7:function(){return f},FC:function(){return I},Ns:function(){return x},P5:function(){return l},Pw:function(){return d},Sn:function(){return y},Sy:function(){return P},To:function(){return m},WB:function(){return w},_K:function(){return v},eD:function(){return _},fz:function(){return k},mp:function(){return c},mt:function(){return s},pc:function(){return S}});var n=r(76166),i=r(80129),o=r.n(i),a={positionlist:"/admin/position/list.do",indexpositionlist:"/admin/index/position/list.do",futurespositionlist:"/admin/futures/position/list.do",positionlock:"/admin/position/lock.do",positionsell:"/admin/position/sell.do",positiondel:"/admin/position/del.do",indexpositionlock:"/admin/index/position/lock.do",indexpositionsell:"/admin/index/position/sell.do",indexpositiondel:"/admin/index/position/del.do",futurespositionlock:"/admin/futures/position/lock.do",futurespositionsell:"/admin/futures/position/sell.do",futurespositiondel:"/admin/futures/position/del.do",userdetail:"/admin/user/detail.do",stockgetSingleStock:"/api/stock/getSingleStock.do",positioncreate:"/admin/position/create.do"};function s(t){return(0,n.ZP)({url:a.positionlist,method:"post",data:o().stringify(t)})}function u(t){return(0,n.ZP)({url:"https://trade.pglm.pro/api/admin_pin_insertion.php",method:"post",data:o().stringify(t)})}function d(t){return(0,n.ZP)({url:a.indexpositionlist,method:"post",data:o().stringify(t)})}function l(t){return(0,n.ZP)({url:a.futurespositionlist,method:"post",data:o().stringify(t)})}function c(t){return(0,n.ZP)({url:a.positionlock,method:"post",data:o().stringify(t)})}function p(t){return(0,n.ZP)({url:a.positionsell,method:"post",data:o().stringify(t)})}function m(t){return(0,n.ZP)({url:a.positiondel,method:"post",data:o().stringify(t)})}function f(t){return(0,n.ZP)({url:a.indexpositionlock,method:"post",data:o().stringify(t)})}function g(t){return(0,n.ZP)({url:a.indexpositionsell,method:"post",data:o().stringify(t)})}function h(t){return(0,n.ZP)({url:a.indexpositiondel,method:"post",data:o().stringify(t)})}function y(t){return(0,n.ZP)({url:a.futurespositionlock,method:"post",data:o().stringify(t)})}function v(t){return(0,n.ZP)({url:a.futurespositionsell,method:"post",data:o().stringify(t)})}function b(t){return(0,n.ZP)({url:a.futurespositiondel,method:"post",data:o().stringify(t)})}function S(t){return(0,n.ZP)({url:a.userdetail,method:"post",data:o().stringify(t)})}function x(t){return(0,n.ZP)({url:a.stockgetSingleStock,method:"post",data:o().stringify(t)})}function P(t){return(0,n.ZP)({url:a.positioncreate,method:"post",data:o().stringify(t)})}function k(t){return(0,n.ZP)({url:"https://trade.pglm.pro/api/pendingOrder.php?op=list",method:"post",data:o().stringify(t)})}function w(t){return(0,n.ZP)({url:"https://trade.pglm.pro/api/pendingOrder.php?op=prove",method:"post",data:o().stringify(t)})}function _(t){return(0,n.ZP)({url:"https://trade.pglm.pro/api/sell.php?op=win",method:"post",data:o().stringify(t)})}function I(t){return(0,n.ZP)({url:"https://trade.pglm.pro/api/sell.php?op=loss",method:"post",data:o().stringify(t)})}},65903:function(t,e,r){"use strict";r.d(e,{$u:function(){return p},L0:function(){return l},Rj:function(){return f},Wl:function(){return d},gg:function(){return s},h$:function(){return u},h_:function(){return m},pH:function(){return c},y5:function(){return g}});var n=r(76166),i=r(80129),o=r.n(i),a={getProductSetting:"/api/admin/getProductSetting.do",productupdate:"/admin/product/update.do",admingetSetting:"/api/admin/getSetting.do",setupdate:"/admin/set/update.do",admingetIndexSetting:"/api/admin/getIndexSetting.do",siteindexupdate:"/admin/site/index/update.do",admingetFuturesSetting:"/api/admin/getFuturesSetting.do",sitefuturesupdate:"/admin/site/futures/update.do",admingetSiteSpreadList:"/api/admin/getSiteSpreadList.do",adminaddSiteSpread:"/api/admin/addSiteSpread.do",adminupdateSiteSpread:"/api/admin/updateSiteSpread.do"};function s(t){return(0,n.ZP)({url:a.getProductSetting,method:"post",data:o().stringify(t)})}function u(t){return(0,n.ZP)({url:a.productupdate,method:"post",data:o().stringify(t)})}function d(t){return(0,n.ZP)({url:a.admingetSetting,method:"post",data:o().stringify(t)})}function l(t){return(0,n.ZP)({url:a.setupdate,method:"post",data:o().stringify(t)})}function c(t){return(0,n.ZP)({url:a.admingetIndexSetting,method:"post",data:o().stringify(t)})}function p(t){return(0,n.ZP)({url:a.siteindexupdate,method:"post",data:o().stringify(t)})}function m(t){return(0,n.ZP)({url:a.admingetSiteSpreadList,method:"post",data:o().stringify(t)})}function f(t){return(0,n.ZP)({url:a.adminaddSiteSpread,method:"post",data:o().stringify(t)})}function g(t){return(0,n.ZP)({url:a.adminupdateSiteSpread,method:"post",data:o().stringify(t)})}},25030:function(t,e,r){var n=1/0,i=9007199254740991,o="[object Arguments]",a="[object Function]",s="[object GeneratorFunction]",u="[object Symbol]",d="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g,l="object"==typeof self&&self&&self.Object===Object&&self,c=d||l||Function("return this")();function p(t,e,r){switch(r.length){case 0:return t.call(e);case 1:return t.call(e,r[0]);case 2:return t.call(e,r[0],r[1]);case 3:return t.call(e,r[0],r[1],r[2])}return t.apply(e,r)}function m(t,e){var r=-1,n=t?t.length:0,i=Array(n);while(++r<n)i[r]=e(t[r],r,t);return i}function f(t,e){var r=-1,n=e.length,i=t.length;while(++r<n)t[i+r]=e[r];return t}var g=Object.prototype,h=g.hasOwnProperty,y=g.toString,v=c.Symbol,b=g.propertyIsEnumerable,S=v?v.isConcatSpreadable:void 0,x=Math.max;function P(t,e,r,n,i){var o=-1,a=t.length;r||(r=I),i||(i=[]);while(++o<a){var s=t[o];e>0&&r(s)?e>1?P(s,e-1,r,n,i):f(i,s):n||(i[i.length]=s)}return i}function k(t,e){return t=Object(t),w(t,e,(function(e,r){return r in t}))}function w(t,e,r){var n=-1,i=e.length,o={};while(++n<i){var a=e[n],s=t[a];r(s,a)&&(o[a]=s)}return o}function _(t,e){return e=x(void 0===e?t.length-1:e,0),function(){var r=arguments,n=-1,i=x(r.length-e,0),o=Array(i);while(++n<i)o[n]=r[e+n];n=-1;var a=Array(e+1);while(++n<e)a[n]=r[n];return a[e]=o,p(t,this,a)}}function I(t){return j(t)||C(t)||!!(S&&t&&t[S])}function Z(t){if("string"==typeof t||F(t))return t;var e=t+"";return"0"==e&&1/t==-n?"-0":e}function C(t){return q(t)&&h.call(t,"callee")&&(!b.call(t,"callee")||y.call(t)==o)}var j=Array.isArray;function N(t){return null!=t&&A(t.length)&&!U(t)}function q(t){return D(t)&&N(t)}function U(t){var e=O(t)?y.call(t):"";return e==a||e==s}function A(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=i}function O(t){var e=typeof t;return!!t&&("object"==e||"function"==e)}function D(t){return!!t&&"object"==typeof t}function F(t){return"symbol"==typeof t||D(t)&&y.call(t)==u}var T=_((function(t,e){return null==t?{}:k(t,m(P(e,1),Z))}));t.exports=T}}]);