import{_ as x}from"./AuthenticatedLayout-678280a1.js";import{r as h,o as s,c as r,b as t,t as i,F as u,s as b,d as a,j as d,w as c,u as m,i as _,a as l,Z as y}from"./app-bb0d0553.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-487968d4.js";const w={class:"w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"},v={class:"mb-4 text-xl font-medium text-gray-500 dark:text-gray-400"},k={class:"flex items-baseline text-gray-900 dark:text-white"},N=t("span",{class:"text-3xl font-semibold"},"₹",-1),B={class:"text-5xl font-extrabold tracking-tight"},C=t("span",{class:"ml-1 text-xl font-normal text-gray-500 dark:text-gray-400"},"/month",-1),S=t("img",{src:"itimages\\tic.png",style:{height:"20px"},alt:"Example Image"},null,-1),V={class:"form-check-label"},I={__name:"SubscriptinCard",props:{planName:String,planMonth:String,price:Number,features:Array,buttonText:String,buttonId:Number,subscription_id:Number},setup(e){return(p,f)=>{const n=h("Button");return s(),r("div",w,[t("h5",v,i(e.planName),1),t("div",k,[N,t("span",B,i(e.price),1),C]),(s(!0),r(u,null,b(e.features,(o,g)=>(s(),r("div",{key:g,class:"mt-2 mb-2 d-flex"},[S,a("     "),t("label",V,i(o),1)]))),128)),e.subscription_id?(s(),d(n,{key:0,type:"button",class:"no-underline bg-gradient-to-r from-purple-400 via-pink-500 to-purple-600 hover:bg-gradient-to-br text-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center"},{default:c(()=>[a(i(e.buttonText),1)]),_:1})):(s(),d(m(_),{key:1,href:"buy/subscription",method:"get",data:{subscriptin_id:e.buttonId},type:"button",class:"no-underline bg-gradient-to-r from-purple-400 via-pink-500 to-purple-600 hover:bg-gradient-to-br text-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center"},{default:c(()=>[a(i(e.buttonText),1)]),_:1},8,["data"]))])}}},j=t("div",{class:"mb-4 inline-flex w-full overflow-hidden rounded-lg bg-white shadow-md"},[t("div",{class:"flex w-12 items-center justify-center bg-pink-800"},[t("svg",{class:"h-6 w-6 fill-current text-white",viewBox:"0 0 40 40",xmlns:"http://www.w3.org/2000/svg"},[t("path",{d:"M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"})])]),t("div",{class:"-mx-3 px-4 py-2"},[t("div",{class:"mx-3"},[t("span",{class:"font-semibold text-blue-500"},"Info"),t("p",{class:"text-sm text-gray-600"},"Sample table page")])])],-1),T={class:"container-fluid"},$={class:"row"},E={__name:"Subscription",props:{subscriptions:Object,user_subscription_id:Number},setup(e){return(p,f)=>(s(),r(u,null,[l(m(y),{title:"About us"}),l(x,null,{default:c(()=>[j,t("div",T,[t("div",$,[(s(!0),r(u,null,b(e.subscriptions,(n,o)=>(s(),r("div",{key:o,class:"col-md-4 p-6 border-b border-gray-200"},[l(I,{planName:n.title,price:n.mrp,buttonId:n.id,features:n.features,buttonText:"Buy Now",subscription_id:e.user_subscription_id},null,8,["planName","price","buttonId","features","subscription_id"])]))),128))])])]),_:1})],64))}};export{E as default};
