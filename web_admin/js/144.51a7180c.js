"use strict";(self["webpackChunkvue_antd_pro"]=self["webpackChunkvue_antd_pro"]||[]).push([[144],{88144:function(t,a,e){e.r(a),e.d(a,{default:function(){return g}});var n=function(){var t=this,a=t._self._c;return a("page-header-wrapper",[a("a-card",{attrs:{bordered:!1}},[a("a-card",{attrs:{bordered:!1}},[a("div",{staticClass:"table-page-search-wrapper"},[a("a-form",{attrs:{layout:"inline"}},[a("a-row",{attrs:{gutter:48}},[a("a-col",{attrs:{md:12,lg:6,sm:24}},[a("a-form-item",{attrs:{label:"任务类型"}},[a("a-select",{attrs:{placeholder:"请选择任务类型"},model:{value:t.queryParam.taskType,callback:function(a){t.$set(t.queryParam,"taskType",a)},expression:"queryParam.taskType"}},t._l(t.tasktypeList,(function(e,n){return a("a-select-option",{key:n,attrs:{value:e.value}},[t._v(" "+t._s(e.value)+" ")])})),1)],1)],1),a("a-col",{attrs:{md:12,lg:8,sm:24}},[a("a-form-item",[a("span",{staticClass:"table-page-search-submitButtons"},[a("a-button",{attrs:{icon:"redo"},on:{click:t.getqueryParam}},[t._v(" 重置")]),a("a-button",{staticStyle:{"margin-left":"8px"},attrs:{type:"primary",icon:"search"},on:{click:function(a){t.queryParam.pageNum=1,t.getlist()}}},[t._v("查询 ")])],1)])],1)],1)],1)],1)]),a("a-table",{attrs:{bordered:"",loading:t.loading,pagination:t.pagination,columns:t.columns,"data-source":t.datalist,rowKey:"id"},scopedSlots:t._u([{key:"isSuccess",fn:function(e,n){return a("span",{},[[a("a-tag",{attrs:{color:0==n.isSuccess?"green":1==n.isSuccess?"red":""}},[t._v(" "+t._s(0==n.isSuccess?"成功":1==n.isSuccess?"失败":""))])]],2)}}])})],1)],1)},s=[],i=e(84180),r=e(30381),o=e.n(r),u={name:"ScheduledTasks",data:function(){var t=this;return{columns:[{title:"任务类型",dataIndex:"taskType",align:"center"},{title:"任务目标",dataIndex:"taskTarget",align:"center"},{title:"任务状态",dataIndex:"isSuccess",align:"center",scopedSlots:{customRender:"isSuccess"}},{title:"注册时间",dataIndex:"addTime",align:"center",width:180,customRender:function(t,a,e){return t?o()(t).format("YYYY-MM-DD HH:mm:ss"):""}}],pagination:{total:0,pageSize:10,showSizeChanger:!0,pageSizeOptions:["10","20","50","100"],onShowSizeChange:function(a,e){return t.onSizeChange(a,e)},onChange:function(a,e){return t.onPageChange(a,e)},showTotal:function(t){return"共有 ".concat(t," 条数据")}},loading:!1,queryParam:{pageNum:1,pageSize:10,taskType:void 0},datalist:[],tasktypeList:[{value:"扣除留仓费"},{value:"定时任务强制平仓-浮动盈亏"},{value:"强平任务-股票持仓"},{value:"强平任务-指数持仓"},{value:"管理员修改金额"}]}},created:function(){this.getlist()},methods:{getqueryParam:function(){this.queryParam={pageNum:1,pageSize:10,taskType:void 0}},getlist:function(){var t=this,a=this;this.loading=!0,(0,i.bo)(this.queryParam).then((function(e){t.datalist=e.data.list,t.pagination.total=e.data.total,setTimeout((function(){a.loading=!1}),500)}))},onPageChange:function(t,a){this.queryParam.pageNum=t,this.getlist()},onSizeChange:function(t,a){this.queryParam.pageNum=t,this.queryParam.pageSize=a,this.getlist()}}},l=u,c=e(70713),d=(0,c.Z)(l,n,s,!1,null,"51ceca78",null),g=d.exports},84180:function(t,a,e){e.d(a,{Pr:function(){return l},RD:function(){return o},bo:function(){return u},tt:function(){return c}});var n=e(76166),s=e(80129),i=e.n(s),r={logloginList:"/admin/log/loginList.do",logtaskList:"/admin/log/taskList.do",logsmsList:"/admin/log/smsList.do",logmessageList:"/admin/log/messageList.do"};function o(t){return(0,n.ZP)({url:r.logloginList,method:"post",data:i().stringify(t)})}function u(t){return(0,n.ZP)({url:r.logtaskList,method:"post",data:i().stringify(t)})}function l(t){return(0,n.ZP)({url:r.logsmsList,method:"post",data:i().stringify(t)})}function c(t){return(0,n.ZP)({url:r.logmessageList,method:"post",data:i().stringify(t)})}}}]);