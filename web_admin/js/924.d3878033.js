(self["webpackChunkvue_antd_pro"]=self["webpackChunkvue_antd_pro"]||[]).push([[924],{29924:function(t,e,r){"use strict";r.r(e),r.d(e,{default:function(){return g}});var a=function(){var t=this,e=t._self._c;return e("page-header-wrapper",[e("a-card",{attrs:{bordered:!1}},[e("div",{staticClass:"table-page-search-wrapper"},[e("a-form",{attrs:{layout:"inline"}},[e("a-row",{attrs:{gutter:48}},[e("a-col",{attrs:{md:12,lg:6,sm:24}},[e("a-form-item",[e("span",{staticClass:"table-page-search-submitButtons"},[e("a-button",{staticStyle:{"margin-left":"8px"},attrs:{type:"primary",icon:"plus"},on:{click:function(e){t.addUserdialog=!0,t.currentDetails=""}}},[t._v(" 添加轮播图")])],1)])],1)],1)],1)],1)]),e("a-card",{attrs:{bordered:!1}},[e("a-table",{attrs:{bordered:"",loading:t.loading,pagination:t.pagination,columns:t.columns,"data-source":t.datalist,rowKey:"id"},scopedSlots:t._u([{key:"bannerUrl",fn:function(t){return e("span",{},[[e("img",{staticStyle:{width:"120px",height:"50px"},attrs:{src:t,alt:""}})]],2)}},{key:"isPc",fn:function(r){return e("span",{},[[e("div",[e("a-tag",{attrs:{color:1==r?"red":"green"}},[t._v(" "+t._s(1==r?"隐藏":"显示")+" ")])],1)]],2)}},{key:"isM",fn:function(r){return e("span",{},[[e("div",[e("a-tag",{attrs:{color:1==r?"red":"green"}},[t._v(" "+t._s(1==r?"隐藏":"显示")+" ")])],1)]],2)}},{key:"action",fn:function(r,a){return[e("a",{attrs:{slot:"action",href:"javascript:;"},on:{click:function(e){return t.geteditbaseCurrency(a)}},slot:"action"},[t._v("修改轮播图")]),e("a-divider",{attrs:{type:"vertical"}}),e("a",{attrs:{slot:"action",href:"javascript:;"},on:{click:function(e){return t.deletebaseCurrency(a.id)}},slot:"action"},[t._v("删除轮播图")])]}}])})],1),e("a-modal",{attrs:{title:t.currentDetails?"修改轮播图图片":"添加轮播图图片",width:800,visible:t.addUserdialog,confirmLoading:t.addUserDialogloading},on:{ok:t.OkaddUserdialog,cancel:t.CanceladdUserdialog}},[e("a-form",{ref:"addUserform",attrs:{form:t.addUserform}},[e("a-form-item",{attrs:{label:"排序",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["isOrder",{rules:[{required:!0,message:"排序值越大显示越靠前"}]}],expression:"['isOrder', { rules: [{ required: true, message: '排序值越大显示越靠前', }] }]"}],attrs:{placeholder:"请输入排序"}})],1),e("a-form-item",{attrs:{label:"标题",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["banTitle",{}],expression:"['banTitle', { }]"}],attrs:{placeholder:"请输入标题"}})],1),e("a-form-item",{attrs:{label:"描述",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["banDesc",{}],expression:"['banDesc', { }]"}],attrs:{placeholder:"请输入描述"}})],1),e("a-form-item",{attrs:{label:"链接",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-input",{directives:[{name:"decorator",rawName:"v-decorator",value:["targetUrl",{}],expression:"['targetUrl', {}]"}],attrs:{placeholder:"请输入链接"}})],1),e("a-form-item",{attrs:{label:"PC端是否显示",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["isPc",{}],expression:"['isPc', { }]"}],attrs:{placeholder:"请选择显示状态"}},[e("a-select-option",{attrs:{value:0}},[t._v("显示")]),e("a-select-option",{attrs:{value:1}},[t._v("隐藏")])],1)],1),e("a-form-item",{attrs:{label:"移动端是否显示",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-select",{directives:[{name:"decorator",rawName:"v-decorator",value:["isM",{}],expression:"['isM', {}]"}],attrs:{placeholder:"请选择显示状态"}},[e("a-select-option",{attrs:{value:0}},[t._v("显示")]),e("a-select-option",{attrs:{value:1}},[t._v("隐藏")])],1)],1),e("a-form-item",{attrs:{label:"轮播图图片",labelCol:t.labelCol,wrapperCol:t.wrapperCol}},[e("a-upload",{directives:[{name:"decorator",rawName:"v-decorator",value:["bannerUrl",{valuePropName:"file",rules:[{required:!0,message:"请上传轮播图图片"}]}],expression:"['bannerUrl', { valuePropName: 'file',rules: [{ required: true, message: '请上传轮播图图片', }] }]"}],staticClass:"avatar-uploader",staticStyle:{width:"200px"},attrs:{name:"avatar","list-type":"picture-card",accept:".jpg,.jpeg,.png",showUploadList:!1,customRequest:t.customRequest}},[t.bannerUrl?e("img",{staticStyle:{width:"100%"},attrs:{src:t.bannerUrl,alt:"avatar"}}):e("div",[e("a-icon",{attrs:{type:t.imgloading?"loading":"plus"}})],1)])],1)],1)],1)],1)},n=[],i=(r(41539),r(54747),r(59685)),o=r(30381),s=r.n(o),l=r(25030),d=r.n(l),u={name:"Basecurrency",data:function(){var t=this;return{columns:[{title:"图片",dataIndex:"bannerUrl",align:"center",scopedSlots:{customRender:"bannerUrl"}},{title:"排序",dataIndex:"isOrder",align:"center"},{title:"PC是否显示",dataIndex:"isPc",align:"center",scopedSlots:{customRender:"isPc"}},{title:"移动端是否显示",dataIndex:"isM",align:"center",scopedSlots:{customRender:"isM"}},{title:"添加时间",dataIndex:"addTime",align:"center",width:180,customRender:function(t,e,r){return t?s()(t).format("YYYY-MM-DD HH:mm:ss"):""}},{title:"操作",key:"action",align:"center",fixed:"right",width:200,scopedSlots:{customRender:"action"}}],pagination:{total:0,pageSize:10,showSizeChanger:!0,pageSizeOptions:["10","20","50","100"],onShowSizeChange:function(e,r){return t.onSizeChange(e,r)},onChange:function(e,r){return t.onPageChange(e,r)},showTotal:function(t){return"共有 ".concat(t," 条数据")}},loading:!1,queryParam:{pageNum:1,pageSize:10},datalist:[],addUserdialog:!1,addUserDialogloading:!1,addUserform:this.$form.createForm(this),labelCol:{xs:{span:24},sm:{span:5}},wrapperCol:{xs:{span:24},sm:{span:18}},fields:["bannerUrl","isOrder","isPc","isM","banDesc","banTitle","targetUrl"],currentDetails:"",bannerUrl:"",imgloading:!1}},created:function(){this.getlist()},methods:{deletebaseCurrency:function(t){var e=this;this.$confirm({title:"提示",content:"确认删除轮播图？此操作不可恢复",onOk:function(){var r={id:t};(0,i.iH)(r).then((function(t){0==t.status?(e.$message.success({content:t.msg,duration:2}),e.getlist()):e.$message.error({content:t.msg})}))},onCancel:function(){console.log("Cancel")}})},customRequest:function(t){var e=this;this.imgloading=!0;var r=new FormData;r.append("upload_file",t.file),(0,i.k4)(r).then((function(t){0==t.status?(e.bannerUrl=t.data.url,e.addUserform.setFieldsValue({bannerUrl:t.data.url})):e.$message.error({content:"上传失败请检查图片类型!"}),e.imgloading=!1}))},geteditbaseCurrency:function(t){var e=this;this.currentDetails=t,this.bannerUrl=t.bannerUrl,this.addUserdialog=!0,this.fields.forEach((function(t){return e.addUserform.getFieldDecorator(t)})),this.addUserform.setFieldsValue(d()(t,this.fields))},CanceladdUserdialog:function(){this.addUserdialog=!1;var t=this.$refs.addUserform.form;t.resetFields(),this.bannerUrl=""},OkaddUserdialog:function(){var t=this,e=this.$refs.addUserform.form;e.validateFields((function(r,a){r||(t.addUserDialogloading=!0,""!=t.currentDetails?(a.id=t.currentDetails.id,console.log(a),(0,i.ls)(a).then((function(r){0==r.status?(t.addUserdialog=!1,t.$message.success({content:"修改成功",duration:2}),e.resetFields(),t.getlist()):t.$message.error({content:r.msg}),t.addUserDialogloading=!1}))):(a.id=0,(0,i.gg)(a).then((function(r){0==r.status?(t.addUserdialog=!1,t.$message.success({content:"添加成功",duration:2}),e.resetFields(),t.getlist()):t.$message.error({content:r.msg}),t.addUserDialogloading=!1}))),t.bannerUrl="")}))},getlist:function(){var t=this;this.loading=!0,(0,i.YU)(this.queryParam).then((function(e){t.datalist=e.data.list,t.pagination.total=e.data.total,t.loading=!1}))},onPageChange:function(t,e){this.queryParam.pageNum=t,this.getlist()},onSizeChange:function(t,e){this.queryParam.pageNum=t,this.queryParam.pageSize=e,this.getlist()}}},c=u,p=r(70713),f=(0,p.Z)(c,a,n,!1,null,"5180abb9",null),g=f.exports},25030:function(t,e,r){var a=1/0,n=9007199254740991,i="[object Arguments]",o="[object Function]",s="[object GeneratorFunction]",l="[object Symbol]",d="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g,u="object"==typeof self&&self&&self.Object===Object&&self,c=d||u||Function("return this")();function p(t,e,r){switch(r.length){case 0:return t.call(e);case 1:return t.call(e,r[0]);case 2:return t.call(e,r[0],r[1]);case 3:return t.call(e,r[0],r[1],r[2])}return t.apply(e,r)}function f(t,e){var r=-1,a=t?t.length:0,n=Array(a);while(++r<a)n[r]=e(t[r],r,t);return n}function g(t,e){var r=-1,a=e.length,n=t.length;while(++r<a)t[n+r]=e[r];return t}var m=Object.prototype,h=m.hasOwnProperty,b=m.toString,v=c.Symbol,y=m.propertyIsEnumerable,C=v?v.isConcatSpreadable:void 0,w=Math.max;function U(t,e,r,a,n){var i=-1,o=t.length;r||(r=k),n||(n=[]);while(++i<o){var s=t[i];e>0&&r(s)?e>1?U(s,e-1,r,a,n):g(n,s):a||(n[n.length]=s)}return n}function P(t,e){return t=Object(t),x(t,e,(function(e,r){return r in t}))}function x(t,e,r){var a=-1,n=e.length,i={};while(++a<n){var o=e[a],s=t[o];r(s,o)&&(i[o]=s)}return i}function S(t,e){return e=w(void 0===e?t.length-1:e,0),function(){var r=arguments,a=-1,n=w(r.length-e,0),i=Array(n);while(++a<n)i[a]=r[e+a];a=-1;var o=Array(e+1);while(++a<e)o[a]=r[a];return o[e]=i,p(t,this,o)}}function k(t){return D(t)||j(t)||!!(C&&t&&t[C])}function _(t){if("string"==typeof t||I(t))return t;var e=t+"";return"0"==e&&1/t==-a?"-0":e}function j(t){return O(t)&&h.call(t,"callee")&&(!y.call(t,"callee")||b.call(t)==i)}var D=Array.isArray;function Z(t){return null!=t&&N(t.length)&&!F(t)}function O(t){return $(t)&&Z(t)}function F(t){var e=q(t)?b.call(t):"";return e==o||e==s}function N(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=n}function q(t){var e=typeof t;return!!t&&("object"==e||"function"==e)}function $(t){return!!t&&"object"==typeof t}function I(t){return"symbol"==typeof t||$(t)&&b.call(t)==l}var A=S((function(t,e){return null==t?{}:P(t,f(U(e,1),_))}));t.exports=A},59685:function(t,e,r){"use strict";r.d(e,{Fl:function(){return d},IV:function(){return C},JO:function(){return h},Nc:function(){return b},YU:function(){return p},Zn:function(){return u},c2:function(){return y},gg:function(){return g},iH:function(){return m},k4:function(){return l},kW:function(){return s},ls:function(){return f},mQ:function(){return w},t7:function(){return c},vr:function(){return v}});var a=r(27370),n=r(80129),i=r.n(n),o={artlist:"/admin/art/list.do",adminupload:"/admin/upload.do",artadd:"/admin/art/add.do",artupdate:"/admin/art/update.do",artdelArt:"/admin/art/delArt.do",bannerslist:"/admin/banners/list.do",bannersupdate:"/admin/banners/update.do",bannersadd:"/admin/banners/add.do",bannersdelete:"/admin/banners/delete.do",paylist:"/admin/pay/list.do",paydel:"/admin/pay/del.do",payadd:"/admin/pay/add.do",payupdate:"/admin/pay/update.do",sitegetInfo:"/api/site/getInfo.do",infoupdate:"/admin/info/update.do"};function s(t){return(0,a.ZP)({url:o.artlist,method:"post",data:i().stringify(t)})}function l(t){return(0,a.ZP)({url:o.adminupload,method:"post",data:t})}function d(t){return(0,a.ZP)({url:o.artadd,method:"post",data:i().stringify(t)})}function u(t){return(0,a.ZP)({url:o.artupdate,method:"post",data:i().stringify(t)})}function c(t){return(0,a.ZP)({url:o.artdelArt,method:"post",data:i().stringify(t)})}function p(t){return(0,a.ZP)({url:o.bannerslist,method:"post",data:i().stringify(t)})}function f(t){return(0,a.ZP)({url:o.bannersupdate,method:"post",data:i().stringify(t)})}function g(t){return(0,a.ZP)({url:o.bannersadd,method:"post",data:i().stringify(t)})}function m(t){return(0,a.ZP)({url:o.bannersdelete,method:"post",data:i().stringify(t)})}function h(t){return(0,a.ZP)({url:o.paylist,method:"post",data:i().stringify(t)})}function b(t){return(0,a.ZP)({url:o.paydel,method:"post",data:i().stringify(t)})}function v(t){return(0,a.ZP)({url:o.payadd,method:"post",data:i().stringify(t)})}function y(t){return(0,a.ZP)({url:o.payupdate,method:"post",data:i().stringify(t)})}function C(t){return(0,a.ZP)({url:o.sitegetInfo,method:"post",data:i().stringify(t)})}function w(t){return(0,a.ZP)({url:o.infoupdate,method:"post",data:i().stringify(t)})}}}]);