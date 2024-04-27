import{_ as d}from"./AuthenticatedLayout-7db831b2.js";import{_ as c}from"./Pagination-46dcd79c.js";import{o as s,c as a,a as r,u as x,w as o,F as i,Z as p,d as b,b as e,s as m,t as n}from"./app-409dadc9.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-ba8e6649.js";const g=e("div",{class:"mb-4 inline-flex w-full overflow-hidden rounded-lg bg-white shadow-md"},[e("div",{class:"flex w-12 items-center justify-center bg-pink-800"},[e("svg",{class:"h-6 w-6 fill-current text-white",viewBox:"0 0 40 40",xmlns:"http://www.w3.org/2000/svg"},[e("path",{d:"M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"})])]),e("div",{class:"-mx-3 px-4 py-2"},[e("div",{class:"mx-3"},[e("span",{class:"font-semibold text-blue-500"},"Info"),e("p",{class:"text-sm text-gray-600"},"Sample table page")])])],-1),w={class:"inline-block min-w-full overflow-hidden rounded-lg shadow"},f={class:"w-full whitespace-no-wrap"},h=e("thead",null,[e("tr",{class:"border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"},[e("th",{class:"border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"}," Name "),e("th",{class:"border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"}," Email ")])],-1),_={class:"border-b border-gray-200 bg-white px-5 py-5 text-sm"},u={class:"text-gray-900 whitespace-no-wrap"},y={class:"border-b border-gray-200 bg-white px-5 py-5 text-sm"},k={class:"text-gray-900 whitespace-no-wrap"},v={class:"flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"},M={__name:"Index",props:{users:Object},setup(l){return(V,C)=>(s(),a(i,null,[r(x(p),{title:"Users"}),r(d,null,{header:o(()=>[b(" Users ")]),default:o(()=>[g,e("div",w,[e("table",f,[h,e("tbody",null,[(s(!0),a(i,null,m(l.users.data,t=>(s(),a("tr",{key:t.id,class:"text-gray-700"},[e("td",_,[e("p",u,n(t.name),1)]),e("td",y,[e("p",k,n(t.email),1)])]))),128))])]),e("div",v,[r(c,{links:l.users.links},null,8,["links"])])])]),_:1})],64))}};export{M as default};