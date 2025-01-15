import{T as V,p as h,o as s,c,b as t,a as w,w as B,q as E,F as p,x as f,t as u,f as S}from"./app-edfd4d7d.js";const D=t("h2",{class:"font-medium text-center text-white font-weight-bolder"}," INVOICE ",-1),W=t("i",{class:"fa-solid fa-bullseye"},null,-1),R=[W],q={key:0},O={class:"-mt-4 rounded-lg shadow table-responsive"},U={class:"table table-bordered"},A=t("thead",{class:"table-dark"},[t("tr",null,[t("th",null,"S.NO."),t("th",null,"Description of Product"),t("th",null,"HSN Code"),t("th",null,"Quantity"),t("th",null,"Unit"),t("th",null,"Weight"),t("th",null,"Rate"),t("th",null,"Taxable Value"),t("th",null,"Action")])],-1),H=["onInput"],I=t("i",{class:"fa fa-plus","aria-hidden":"true"},null,-1),L=[I],P=["onClick"],Q=t("i",{class:"fa fa-minus","aria-hidden":"true"},null,-1),$=[Q],j=t("td",null,null,-1),z=t("td",null,null,-1),G=t("td",null,null,-1),J=t("td",null,null,-1),K=t("td",{class:"font-bold"},"Total Weight:",-1),M={class:"font-bold"},X=t("td",{class:"font-bold"},"Total:",-1),Y={class:"font-bold"},Z=t("td",null,null,-1),lt={__name:"Editable",setup(tt){const y=V({}),o=h([["","","","","",""]]),m=h([]),r=h(!0),C=(e,l,n)=>{if([2,4,5].includes(l)){const a=n.target.textContent;isNaN(a)?n.target.textContent=0:o.value[e][l]=parseFloat(a)}else o.value[e][l]=n.target.textContent},x=e=>{const l=["","","","","",""];o.value.splice(e+1,0,l)},k=e=>{o.value.splice(e,1)},F=e=>e+1,v=e=>{const l=parseFloat(o.value[e][2])||0,n=parseFloat(o.value[e][5])||0;return(l*n).toFixed(2)},b=()=>{let e=0;for(let l=0;l<o.value.length;l++)e+=parseFloat(v(l));return e.toFixed(2)},g=()=>{let e=0;for(let l=0;l<o.value.length;l++){const n=parseFloat(o.value[l][4])||0;e+=n}return e.toFixed(2)},T=()=>{const e=document.getElementById("invoice_no").value,l=document.getElementById("customer").value,n=document.getElementById("company").value,a=document.getElementById("vehical_no").value,i=0;m.value[i]={invoice:e,customer:l,company:n,vehical_no:a,totalWeight:g(),totalTaxableValue:b()};const d=o.value,_=m.value;y.post(route("performa.customer.store.bill",{invoicedata:d,invoicedetails:_}),{preserveScroll:!0})},N=()=>{r.value=!r.value};return(e,l)=>(s(),c(p,null,[t("div",{class:"mt-5 bg-purple-700 rounded-md position-relative"},[D,t("span",{class:"text-white cursor-pointer top-2 right-2 position-absolute",onClick:N},R)]),w(E,{name:"slide-fade"},{default:B(()=>[r.value?(s(),c("div",q,[t("div",O,[t("table",U,[A,t("tbody",null,[(s(!0),c(p,null,f(o.value,(n,a)=>(s(),c("tr",{key:a},[t("td",null,u(F(a)),1),(s(!0),c(p,null,f(n,(i,d)=>(s(),c("td",{key:d,onInput:_=>C(a,d,_),contenteditable:""},u(i),41,H))),128)),t("td",null,u(v(a)),1),t("td",null,[a===o.value.length-1?(s(),c("span",{key:0,class:"cursor-pointer badge rounded-pill text-bg-primary",onClick:x},L)):(s(),c("span",{key:1,class:"cursor-pointer badge rounded-pill text-bg-danger",onClick:i=>k(a)},$,8,P))])]))),128)),t("tr",null,[j,z,G,J,K,t("td",M,u(g()),1),X,t("td",Y,u(b()),1),Z])])])]),t("button",{class:"text-white btn btn-info text-bold",onClick:T}," Submit ")])):S("",!0)]),_:1})],64))}};export{lt as default};
