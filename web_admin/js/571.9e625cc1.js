(self["webpackChunkvue_antd_pro"]=self["webpackChunkvue_antd_pro"]||[]).push([[571],{38571:function(e,t,a){"use strict";a.r(t),a.d(t,{default:function(){return g}});a(68309),a(56977),a(9653);var r=function(){var e=this,t=e._self._c;return t("page-header-wrapper",[t("a-card",{attrs:{bordered:!1}},[t("div",{staticClass:"table-page-search-wrapper"},[t("a-form",{attrs:{layout:"inline"}},[t("a-row",{attrs:{gutter:48}},[t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"股票类型"}},[t("a-select",{attrs:{placeholder:"请选择股票类型"},model:{value:e.queryParam.stockPlate,callback:function(t){e.$set(e.queryParam,"stockPlate",t)},expression:"queryParam.stockPlate"}},[t("a-select-option",{attrs:{value:"A股"}},[e._v("股票")]),t("a-select-option",{attrs:{value:"科创"}},[e._v("科创")])],1)],1)],1),t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"沪深股"}},[t("a-select",{attrs:{placeholder:"请选择显示状态"},model:{value:e.queryParam.stockType,callback:function(t){e.$set(e.queryParam,"stockType",t)},expression:"queryParam.stockType"}},[t("a-select-option",{attrs:{value:"india"}},[e._v("印股")]),t("a-select-option",{attrs:{value:"mys"}},[e._v("马股")]),t("a-select-option",{attrs:{value:"hk"}},[e._v("港股")]),t("a-select-option",{attrs:{value:"us"}},[e._v("美股")])],1)],1)],1),t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"锁定状态"}},[t("a-select",{attrs:{placeholder:"请选择锁定状态"},model:{value:e.queryParam.lockState,callback:function(t){e.$set(e.queryParam,"lockState",t)},expression:"queryParam.lockState"}},[t("a-select-option",{attrs:{value:0}},[e._v("正常")]),t("a-select-option",{attrs:{value:1}},[e._v("锁定")])],1)],1)],1),t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"显示状态"}},[t("a-select",{attrs:{placeholder:"请选择显示状态"},model:{value:e.queryParam.showState,callback:function(t){e.$set(e.queryParam,"showState",t)},expression:"queryParam.showState"}},[t("a-select-option",{attrs:{value:0}},[e._v("显示")]),t("a-select-option",{attrs:{value:1}},[e._v("隐藏")])],1)],1)],1)],1),t("a-row",{attrs:{gutter:48}},[t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"股票代码"}},[t("a-input",{staticStyle:{width:"100%"},attrs:{placeholder:"请输入股票代码"},model:{value:e.queryParam.code,callback:function(t){e.$set(e.queryParam,"code",t)},expression:"queryParam.code"}})],1)],1),t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",{attrs:{label:"股票名称"}},[t("a-input",{staticStyle:{width:"100%"},attrs:{placeholder:"请输入股票名称"},model:{value:e.queryParam.name,callback:function(t){e.$set(e.queryParam,"name",t)},expression:"queryParam.name"}})],1)],1),t("a-col",{attrs:{md:12,lg:6,sm:24}},[t("a-form-item",[t("span",{staticClass:"table-page-search-submitButtons"},[t("a-button",{attrs:{icon:"redo"},on:{click:e.getqueryParam}},[e._v(" 重置")]),t("a-button",{staticStyle:{"margin-left":"8px"},attrs:{type:"primary",icon:"search"},on:{click:function(t){e.queryParam.pageNum=1,e.pagination.current=1,e.getlist()}}},[e._v("查询 ")]),t("a-button",{staticStyle:{"margin-left":"8px"},attrs:{type:"primary",icon:"plus"},on:{click:function(t){e.addUserdialog=!0}}},[e._v(" 添加股票")])],1)])],1)],1)],1)],1)]),t("a-card",{attrs:{bordered:!1}},[t("a-table",{attrs:{bordered:"",loading:e.loading,pagination:e.pagination,columns:e.columns,"data-source":e.datalist,rowKey:"id"},scopedSlots:e._u([{key:"stockName",fn:function(a,r){return t("span",{},[[t("div",[t("span",{staticStyle:{"margin-right":"10px"}},[e._v(e._s(r.stockName))]),t("a-tag",{attrs:{color:"green"}},[e._v(e._s(r.stockCode)+" ")])],1)]],2)}},{key:"stockType",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:"red"}},[e._v(e._s("sz"==r.stockType?"深股":"sh"==r.stockType?"沪股":"bj"==r.stockType?"京股":"hk"==r.stockType?"港股":"us"==r.stockType?"美股":"")+" ")])],1)]],2)}},{key:"nowPrice",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:r.hcrate<0?"green":r.hcrate>0?"red":""}},[e._v(e._s(Number(r.nowPrice).toFixed(2))+" ")])],1)]],2)}},{key:"hcrate",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:r.hcrate<0?"green":r.hcrate>0?"red":""}},[e._v(" "+e._s(r.hcrate)+"% ")])],1)]],2)}},{key:"day3Rate",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:r.day3Rate<0?"green":r.day3Rate>0?"red":""}},[e._v(" "+e._s(r.day3Rate)+"% ")])],1)]],2)}},{key:"spreadRate",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:r.spreadRate<0?"green":r.spreadRate>0?"red":""}},[e._v(" "+e._s(100*r.spreadRate)+"% ")])],1)]],2)}},{key:"isShow",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:0==r.isShow?"green":1==r.isShow?"red":""}},[e._v(" "+e._s(0==r.isShow?"显示":"隐藏")+" ")])],1)]],2)}},{key:"isLock",fn:function(a,r){return t("span",{},[[t("div",[t("a-tag",{attrs:{color:0==r.isLock?"green":1==r.isLock?"red":""}},[e._v(" "+e._s(0==r.isLock?"正常":"锁定")+" ")])],1)]],2)}},{key:"action",fn:function(a,r){return[t("a",{attrs:{slot:"action",href:"javascript:;"},on:{click:function(t){return e.getisShow(r.id,r.isShow)}},slot:"action"},[e._v(e._s(0==r.isShow?"隐藏股票":"显示股票"))]),t("a-divider",{attrs:{type:"vertical"}}),t("a",{attrs:{slot:"action",href:"javascript:;"},on:{click:function(t){return e.getisLock(r.id,r.isLock)}},slot:"action"},[e._v(e._s(0==r.isLock?"锁定股票":"解锁股票"))]),t("a-divider",{attrs:{type:"vertical"}}),t("a",{attrs:{slot:"action",href:"javascript:;"},on:{click:function(t){return e.geteditStock(r)}},slot:"action"},[e._v(e._s("修改股票"))])]}}])})],1),t("a-modal",{attrs:{title:"添加股票",width:500,visible:e.addUserdialog,confirmLoading:e.addUserDialogloading},on:{ok:e.OkaddUserdialog,cancel:e.CanceladdUserdialog}},[t("a-form",{ref:"addUserform",attrs:{form:e.addUserform}},[t("a-form-item",{attrs:{label:"股票名称",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockName",{rules:[{required:!0,message:"请输入股票名称"}]}],expression:"['stockName', { rules: [{ required: true, message: '请输入股票名称', }] }]"}],attrs:{placeholder:"请输入股票名称"}})],1),t("a-form-item",{attrs:{label:"股票代码",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockCode",{rules:[{required:!0,message:"请输入股票代码"}]}],expression:"['stockCode', { rules: [{ required: true, message: '请输入股票代码', }] }]"}],attrs:{placeholder:"请输入股票代码"}})],1),t("a-form-item",{attrs:{label:"股票类型",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockType",{rules:[{required:!0,message:"请选择股票类型"}]}],expression:"['stockType', { rules: [{ required: true, message: '请选择股票类型', }] }]"}],attrs:{placeholder:"请选择股票类型"}},[t("a-select-option",{attrs:{value:"sh"}},[e._v("沪股")]),t("a-select-option",{attrs:{value:"sz"}},[e._v("深股")]),t("a-select-option",{attrs:{value:"bj"}},[e._v("京股")]),t("a-select-option",{attrs:{value:"hk"}},[e._v("港股")]),t("a-select-option",{attrs:{value:"us"}},[e._v("美股")])],1)],1),t("a-form-item",{attrs:{label:"科创板股票",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockPlate",{rules:[{required:!0,message:"请选择科创板股票"}]}],expression:"['stockPlate', { rules: [{ required: true, message: '请选择科创板股票', }] }]"}],attrs:{placeholder:"请选择科创板股票"}},[t("a-select-option",{attrs:{value:"A股"}},[e._v("否")]),t("a-select-option",{attrs:{value:"科创"}},[e._v("是")])],1)],1),t("a-form-item",{attrs:{label:"锁定状态",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["isLock",{rules:[{required:!0,message:"请选择锁定状态"}]}],expression:"['isLock', { rules: [{ required: true, message: '请选择锁定状态', }] }]"}],attrs:{placeholder:"请选择锁定状态"}},[t("a-select-option",{attrs:{value:"0"}},[e._v("未锁定")]),t("a-select-option",{attrs:{value:"1"}},[e._v("锁定")])],1)],1),t("a-form-item",{attrs:{label:"显示状态",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["isShow",{rules:[{required:!0,message:"请选择显示状态"}]}],expression:"['isShow', { rules: [{ required: true, message: '请选择显示状态', }] }]"}],attrs:{placeholder:"请选择显示状态"}},[t("a-select-option",{attrs:{value:"0"}},[e._v("显示")]),t("a-select-option",{attrs:{value:"1"}},[e._v("不显示")])],1)],1)],1)],1),t("a-modal",{attrs:{title:"修改股票",width:500,visible:e.editStockdialog,confirmLoading:e.editStockdialogloading},on:{ok:e.OkeditStockdialog,cancel:e.CanceleditStockdialog}},[t("a-form",{ref:"editStockform",attrs:{form:e.editStockform}},[t("a-form-item",{attrs:{label:"股票名称",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["stockName",{rules:[{required:!0,message:"请输入股票名称"}]}],expression:"['stockName', { rules: [{ required: true, message: '请输入股票名称', }] }]"}],attrs:{placeholder:"请输入股票名称"}})],1),t("a-form-item",{attrs:{label:"点差费率",labelCol:e.labelCol,wrapperCol:e.wrapperCol}},[t("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["spreadRate",{rules:[{required:!0,message:"请输入点差费率"}]}],expression:"['spreadRate', { rules: [{ required: true, message: '请输入点差费率', }] }]"}],attrs:{placeholder:"请输入点差费率"}})],1)],1)],1)],1)},o=[],s=(a(41539),a(54747),a(7658)),n=a(30381),i=a.n(n),l=a(25030),c=a.n(l),d={name:"Shares",data:function(){var e=this;return{columns:[{title:"股票名称 / 股票代码",dataIndex:"stockName",align:"center",scopedSlots:{customRender:"stockName"}},{title:"类型",dataIndex:"stockType",align:"center",scopedSlots:{customRender:"stockType"}},{title:"现价",dataIndex:"nowPrice",align:"center",scopedSlots:{customRender:"nowPrice"}},{title:"涨跌幅",dataIndex:"hcrate",align:"center",scopedSlots:{customRender:"hcrate"}},{title:"最近3天涨跌",dataIndex:"day3Rate",align:"center",scopedSlots:{customRender:"day3Rate"}},{title:"点差费率",dataIndex:"spreadRate",align:"center",scopedSlots:{customRender:"spreadRate"}},{title:"显示状态",dataIndex:"isShow",align:"center",scopedSlots:{customRender:"isShow"}},{title:"股票状态",dataIndex:"isLock",align:"center",scopedSlots:{customRender:"isLock"}},{title:"添加时间",dataIndex:"addTime",align:"center",customRender:function(e,t,a){return e?i()(e).format("YYYY-MM-DD HH:mm:ss"):""}},{title:"操作",key:"action",align:"center",scopedSlots:{customRender:"action"}}],pagination:{total:0,current:1,pageSize:10,showSizeChanger:!0,pageSizeOptions:["10","20","50","100"],onShowSizeChange:function(t,a){return e.onSizeChange(t,a)},onChange:function(t,a){return e.onPageChange(t,a)},showTotal:function(e){return"共有 ".concat(e," 条数据")}},loading:!1,queryParam:{pageNum:1,pageSize:10,code:"",name:"",stockPlate:"A股",stockType:void 0,showState:void 0,lockState:void 0},datalist:[],labelCol:{xs:{span:24},sm:{span:7}},wrapperCol:{xs:{span:24},sm:{span:13}},addUserform:this.$form.createForm(this),addUserdialog:!1,addUserDialogloading:!1,editStockdialog:!1,editStockdialogloading:!1,editStockform:this.$form.createForm(this),fields:["stockName","spreadRate"],currentid:""}},created:function(){this.getlist()},methods:{geteditStock:function(e){var t=this;this.currentid=e.id,this.editStockdialog=!0,this.fields.forEach((function(e){return t.editStockform.getFieldDecorator(e)})),this.editStockform.setFieldsValue(c()(e,this.fields))},CanceleditStockdialog:function(){this.editStockdialog=!1;var e=this.$refs.editStockform.form;e.resetFields()},OkeditStockdialog:function(){var e=this,t=this.$refs.editStockform.form;t.validateFields((function(a,r){a||(e.editStockdialogloading=!0,r.id=e.currentid,(0,s.kl)(r).then((function(a){0==a.status?(e.editStockdialog=!1,e.$message.success({content:a.msg,duration:2}),t.resetFields(),e.getlist()):e.$message.error({content:a.msg}),e.editStockdialogloading=!1})))}))},CanceladdUserdialog:function(){this.addUserdialog=!1;var e=this.$refs.addUserform.form;e.resetFields()},OkaddUserdialog:function(){var e=this,t=this.$refs.addUserform.form;t.validateFields((function(a,r){a||(e.addUserDialogloading=!0,"A股"==r.stockPlate?r.stockPlate="":r.stockPlate,(0,s.UL)(r).then((function(a){0==a.status?(e.addUserdialog=!1,e.$message.success({content:a.msg,duration:2}),t.resetFields(),e.getinit()):e.$message.error({content:a.msg}),e.addUserDialogloading=!1})))}))},getqueryParam:function(){this.queryParam={pageNum:1,pageSize:10,code:"",name:"",stockPlate:"A股",stockType:void 0,showState:void 0,lockState:void 0}},getinit:function(){this.getqueryParam(),this.pagination.current=1,this.getlist()},getlist:function(){var e=this;this.loading=!0,"A股"==this.queryParam.stockPlate?this.queryParam.stockPlate="":this.queryParam.stockPlate,(0,s.Zx)(this.queryParam).then((function(t){e.datalist=t.data.list,e.pagination.total=t.data.total,""==e.queryParam.stockPlate?e.queryParam.stockPlate="A股":e.queryParam.stockPlate,e.loading=!1}))},getisShow:function(e,t){var a=this;(0,s.u9)({stockId:e}).then((function(e){0===e.status?(a.getlist(),0==t?a.$message.success({content:"隐藏成功",duration:2}):a.$message.success({content:"显示成功",duration:2})):a.$message.error({content:e.msg,duration:2})}))},getisLock:function(e,t){var a=this;(0,s.N1)({stockId:e}).then((function(e){0===e.status?(a.getlist(),0==t?a.$message.success({content:"锁定成功",duration:2}):a.$message.success({content:"解锁成功",duration:2})):a.$message.error({content:e.msg,duration:2})}))},onPageChange:function(e,t){this.queryParam.pageNum=e,this.pagination.current=e,this.getlist()},onSizeChange:function(e,t){this.queryParam.pageNum=e,this.pagination.current=page,this.queryParam.pageSize=t,this.getlist()}}},u=d,m=a(70713),p=(0,m.Z)(u,r,o,!1,null,null,null),g=p.exports},25030:function(e,t,a){var r=1/0,o=9007199254740991,s="[object Arguments]",n="[object Function]",i="[object GeneratorFunction]",l="[object Symbol]",c="object"==typeof a.g&&a.g&&a.g.Object===Object&&a.g,d="object"==typeof self&&self&&self.Object===Object&&self,u=c||d||Function("return this")();function m(e,t,a){switch(a.length){case 0:return e.call(t);case 1:return e.call(t,a[0]);case 2:return e.call(t,a[0],a[1]);case 3:return e.call(t,a[0],a[1],a[2])}return e.apply(t,a)}function p(e,t){var a=-1,r=e?e.length:0,o=Array(r);while(++a<r)o[a]=t(e[a],a,e);return o}function g(e,t){var a=-1,r=t.length,o=e.length;while(++a<r)e[o+a]=t[a];return e}var f=Object.prototype,h=f.hasOwnProperty,v=f.toString,k=u.Symbol,y=f.propertyIsEnumerable,S=k?k.isConcatSpreadable:void 0,b=Math.max;function w(e,t,a,r,o){var s=-1,n=e.length;a||(a=q),o||(o=[]);while(++s<n){var i=e[s];t>0&&a(i)?t>1?w(i,t-1,a,r,o):g(o,i):r||(o[o.length]=i)}return o}function P(e,t){return e=Object(e),C(e,t,(function(t,a){return a in e}))}function C(e,t,a){var r=-1,o=t.length,s={};while(++r<o){var n=t[r],i=e[n];a(i,n)&&(s[n]=i)}return s}function _(e,t){return t=b(void 0===t?e.length-1:t,0),function(){var a=arguments,r=-1,o=b(a.length-t,0),s=Array(o);while(++r<o)s[r]=a[t+r];r=-1;var n=Array(t+1);while(++r<t)n[r]=a[r];return n[t]=s,m(e,this,n)}}function q(e){return N(e)||R(e)||!!(S&&e&&e[S])}function x(e){if("string"==typeof e||A(e))return e;var t=e+"";return"0"==t&&1/e==-r?"-0":t}function R(e){return I(e)&&h.call(e,"callee")&&(!y.call(e,"callee")||v.call(e)==s)}var N=Array.isArray;function $(e){return null!=e&&U(e.length)&&!j(e)}function I(e){return T(e)&&$(e)}function j(e){var t=L(e)?v.call(e):"";return t==n||t==i}function U(e){return"number"==typeof e&&e>-1&&e%1==0&&e<=o}function L(e){var t=typeof e;return!!e&&("object"==t||"function"==t)}function T(e){return!!e&&"object"==typeof e}function A(e){return"symbol"==typeof e||T(e)&&v.call(e)==l}var F=_((function(e,t){return null==e?{}:P(e,p(w(t,1),x))}));e.exports=F},7658:function(e,t,a){"use strict";a.d(t,{A7:function(){return p},N1:function(){return c},RC:function(){return f},UL:function(){return u},Zx:function(){return i},aV:function(){return g},jE:function(){return d},kl:function(){return m},u9:function(){return l}});var r=a(27370),o=a(80129),s=a.n(o),n={stocklist:"/admin/stock/list.do",updateShow:"/admin/stock/updateShow.do",updateLock:"/admin/stock/updateLock.do",indexlist:"/admin/index/list.do",coinlist:"/admin/coin/list.do",futureslist:"/admin/futures/list.do",stockadd:"/admin/stock/add.do",stockupdateStock:"/admin/stock/updateStock.do",indexaddIndex:"/admin/index/addIndex.do",querySingleIndex:"/api/index/querySingleIndex.do",indexupdateIndex:"/admin/index/updateIndex.do",coinadd:"/admin/coin/add.do",coinupdate:"/admin/coin/update.do",coingetSelectCoin:"/admin/coin/getSelectCoin.do",futuresadd:"/admin/futures/add.do",futuresupdate:"/admin/futures/update.do"};function i(e){return(0,r.ZP)({url:n.stocklist,method:"post",data:s().stringify(e)})}function l(e){return(0,r.ZP)({url:n.updateShow,method:"post",data:s().stringify(e)})}function c(e){return(0,r.ZP)({url:n.updateLock,method:"post",data:s().stringify(e)})}function d(e){return(0,r.ZP)({url:n.indexlist,method:"post",data:s().stringify(e)})}function u(e){return(0,r.ZP)({url:n.stockadd,method:"post",data:s().stringify(e)})}function m(e){return(0,r.ZP)({url:n.stockupdateStock,method:"post",data:s().stringify(e)})}function p(e){return(0,r.ZP)({url:n.indexaddIndex,method:"post",data:s().stringify(e)})}function g(e){return(0,r.ZP)({url:n.querySingleIndex,method:"post",data:s().stringify(e)})}function f(e){return(0,r.ZP)({url:n.indexupdateIndex,method:"post",data:s().stringify(e)})}}}]);