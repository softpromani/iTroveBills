import{y as L,S as H,g as y,q as g,o as h,c as w,b as e,m as v,h as p,R as m,a,w as o,n as _,L as f,j as C,u as z,i as k,t as $,d as i,r as b,F as V}from"./app-7f38a0fa.js";import{_ as j}from"./_plugin-vue_export-helper-c27b6911.js";import{F as B}from"./FlashMessages-3c3d7e2a.js";const T={class:"relative"},I={class:"absolute right-0 z-10 mt-2 w-48 overflow-hidden rounded-md bg-white shadow-xl"},S={__name:"Dropdown",props:{align:{default:"right"},width:{default:"48"},contentClasses:{default:()=>["py-1","bg-white"]}},setup(s){const t=s,r=d=>{c.value&&d.key==="Escape"&&(c.value=!1)};L(()=>document.addEventListener("keydown",r)),H(()=>document.removeEventListener("keydown",r));const n=y(()=>({48:"w-48"})[t.width.toString()]),x=y(()=>t.align==="left"?"origin-top-left left-0":t.align==="right"?"origin-top-right right-0":"origin-top"),c=g(!1);return(d,l)=>(h(),w("div",T,[e("div",{onClick:l[0]||(l[0]=u=>c.value=!c.value)},[v(d.$slots,"trigger")]),p(e("div",{class:"fixed inset-0 z-40",onClick:l[1]||(l[1]=u=>c.value=!1)},null,512),[[m,c.value]]),a(f,{"enter-active-class":"transition duration-200 ease-out","enter-from-class":"scale-95 transform opacity-0","enter-to-class":"scale-100 transform opacity-100","leave-active-class":"transition duration-75 ease-in","leave-from-class":"scale-100 transform opacity-100","leave-to-class":"scale-95 transform opacity-0"},{default:o(()=>[p(e("div",{class:_(["absolute z-50 mt-2 rounded-md shadow-lg",[n.value,x.value]]),style:{display:"none"},onClick:l[2]||(l[2]=u=>c.value=!1)},[e("div",I,[v(d.$slots,"content")])],2),[[m,c.value]])]),_:3})]))}},M={__name:"DropdownLink",setup(s){return(t,r)=>(h(),C(z(k),{class:"no-underline block px-4 py-2 text-sm text-gray-700 hover:bg-pink-600 hover:text-white"},{default:o(()=>[v(t.$slots,"default")]),_:3}))}},E={class:"flex items-center justify-between px-6 py-4 bg-white border-b-4 border-pink-900"},D={class:"flex items-center"},N=e("svg",{class:"w-6 h-6",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},[e("path",{d:"M4 6H20M4 12H20M4 18H11",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})],-1),A=[N],O={class:"flex items-center"},F={__name:"Header",setup(s){return(t,r)=>(h(),w("header",E,[e("div",D,[e("button",{onClick:r[0]||(r[0]=n=>t.$page.props.showingMobileMenu=!t.$page.props.showingMobileMenu),class:"text-gray-500 focus:outline-none lg:hidden"},A)]),e("div",O,[a(S,null,{trigger:o(()=>[e("button",{onClick:r[1]||(r[1]=n=>t.dropdownOpen=!t.dropdownOpen),class:"relative block overflow-hidden"},$(t.$page.props.auth.user.name),1)]),content:o(()=>[a(M,{href:t.route("profile.edit"),class:"w-full text-left"},{default:o(()=>[i(" Profile ")]),_:1},8,["href"]),a(M,{class:"w-full text-left",href:t.route("logout"),method:"post",as:"button"},{default:o(()=>[i(" Log out ")]),_:1},8,["href"])]),_:1})])]))}},U={class:"mx-3"},q={__name:"NavLink",props:["href","active"],setup(s){const t=s,r=y(()=>t.active?"no-underline flex items-center mt-4 py-2 px-6 bg-gray-700 bg-opacity-25 text-gray-100":"no-underline flex items-center mt-4 py-2 px-6 text-gray-100");return(n,x)=>(h(),C(z(k),{href:s.href,class:_(r.value)},{default:o(()=>[v(n.$slots,"icon"),e("span",U,[v(n.$slots,"default")])]),_:3},8,["href","class"]))}},P={components:{NavLink:q,Link:k},setup(){let s=g(!1),t=g(!1),r=g(!1),n=g(!1);return{showingTwoLevelMenu:s,showingTwoLevelMenu2:t,showingCustomerMenu:r,showingInvoices:n}}},R={class:"flex items-center justify-center mt-8"},G={class:"flex items-center"},J=["src"],K={class:"mx-2 text-2xl font-semibold text-white"},Q={key:0,class:"mt-10","x-data":"{ isMultiLevelMenuOpen: false }"},W=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"}),e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"})],-1),X=e("svg",{class:"w-5 h-5",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"})],-1),Y=e("svg",{class:"w-5 h-5","aria-hidden":"true",fill:"none","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{d:"M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"})],-1),Z=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"})],-1),ee=e("span",{class:"mx-3"},"Companies",-1),te=[Z,ee],oe={class:"p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner","aria-label":"submenu"},se={class:"px-2 py-1 transition-colors duration-150"},ae={class:"px-2 py-1 transition-colors duration-150"},ne=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"})],-1),ie=e("span",{class:"mx-3"},"Customers",-1),le=[ne,ie],re={class:"p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner","aria-label":"submenu"},de={class:"px-2 py-1 transition-colors duration-150"},ce={class:"px-2 py-1 transition-colors duration-150"},ue=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"})],-1),he=e("span",{class:"mx-3"},"Invoices",-1),pe=[ue,he],me={class:"p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner","aria-label":"submenu"},ve={class:"px-2 py-1 transition-colors duration-150"},fe={class:"px-2 py-1 transition-colors duration-150"},we={key:1,class:"mt-10","x-data":"{ isMultiLevelMenuOpen: false }"},ge=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"}),e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"})],-1),_e=e("svg",{class:"w-5 h-5",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"})],-1),xe=e("span",{class:"d-flex"},[i("Users   "),e("img",{src:"build/images/icon.png",style:{height:"20px"},alt:"Example Image"})],-1),ye=e("svg",{class:"w-5 h-5","aria-hidden":"true",fill:"none","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{d:"M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"})],-1),ke=e("span",{class:"d-flex"},[i("Subscription   "),e("img",{src:"build/images/icon.png",style:{height:"20px"},alt:"Example Image"})],-1),be=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"})],-1),Me=e("span",{class:"mx-3"},"Companies",-1),Ce=[be,Me],ze={class:"p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner","aria-label":"submenu"},$e={class:"px-2 py-1 transition-colors duration-150"},Le=e("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"})],-1),He=e("span",{class:"mx-3"},"Companies",-1),Ve=[Le,He],je={class:"p-2 mx-4 mt-2 space-y-2 overflow-hidden text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner","aria-label":"submenu"},Be={class:"px-2 py-1 transition-colors duration-150"},Te={class:"modal fade",id:"exampleModal",tabindex:"-1","aria-labelledby":"exampleModalLabel","aria-hidden":"true"},Ie={class:"modal-dialog"},Se={class:"modal-content"},Ee=e("div",{class:"modal-body"}," Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam, eos pariatur. Sunt corrupti error voluptatem laudantium facilis reprehenderit, quod nesciunt? ",-1),De={class:"modal-footer"};function Ne(s,t,r,n,x,c){const d=b("nav-link"),l=b("Link");return h(),w(V,null,[e("div",{class:_([s.$page.props.showingMobileMenu?"block":"hidden","fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"]),onClick:t[0]||(t[0]=u=>s.$page.props.showingMobileMenu=!1)},null,2),e("div",{class:_([s.$page.props.showingMobileMenu?"translate-x-0 ease-out":"-translate-x-full ease-in","fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-pink-900 lg:translate-x-0 lg:static lg:inset-0"])},[e("div",R,[e("div",G,[e("img",{src:s.$page.props.auth.user.app_logo??"build/images/it.png",alt:"",srcset:"",style:{height:"70px"}},null,8,J),e("span",K,$(s.$page.props.auth.user.app_name??"Innovation Trove"),1)])]),s.$page.props.auth.user.roles.includes("Seller")?(h(),w("nav",Q,[a(d,{href:s.route("dashboard"),active:s.route().current("dashboard")},{icon:o(()=>[W]),default:o(()=>[i(" Dashboard ")]),_:1},8,["href","active"]),a(d,{href:s.route("users.index"),active:s.route().current("users.index")},{icon:o(()=>[X]),default:o(()=>[i(" Users ")]),_:1},8,["href","active"]),a(d,{href:s.route("subscription.index"),active:s.route().current("subscription.index")},{icon:o(()=>[Y]),default:o(()=>[i(" Subscription ")]),_:1},8,["href","active"]),e("a",{class:"flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle",href:"#",onClick:t[1]||(t[1]=u=>n.showingTwoLevelMenu=!n.showingTwoLevelMenu)},te),a(f,{"enter-to-class":"transition-all duration-300 ease-in-out","enter-from-class":"opacity-25 max-h-0","leave-from-class":"opacity-100 max-h-xl","leave-to-class":"opacity-0 max-h-0"},{default:o(()=>[p(e("div",null,[e("ul",oe,[e("li",se,[a(l,{class:"text-white no-underline first-letter:w-full",href:s.route("company.index")},{default:o(()=>[i("Create Company")]),_:1},8,["href"])]),e("li",ae,[a(l,{class:"text-white no-underline first-letter:w-full",href:s.route("company.view")},{default:o(()=>[i("View Company")]),_:1},8,["href"])])])],512),[[m,n.showingTwoLevelMenu]])]),_:1}),e("a",{class:"flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle",href:"#",onClick:t[2]||(t[2]=u=>n.showingCustomerMenu=!n.showingCustomerMenu)},le),a(f,{"enter-to-class":"transition-all duration-300 ease-in-out","enter-from-class":"opacity-25 max-h-0","leave-from-class":"opacity-100 max-h-xl","leave-to-class":"opacity-0 max-h-0"},{default:o(()=>[p(e("div",null,[e("ul",re,[e("li",de,[a(l,{class:"text-white no-underline first-letter:w-full",href:s.route("company.customer")},{default:o(()=>[i("Create Customer")]),_:1},8,["href"])]),e("li",ce,[a(l,{class:"text-white no-underline first-letter:w-full",href:s.route("company.customer.view")},{default:o(()=>[i("View Customer")]),_:1},8,["href"])])])],512),[[m,n.showingCustomerMenu]])]),_:1}),e("a",{class:"flex items-center px-6 py-2 mt-4 text-gray-100 no-underline dropdown-toggle",href:"#",onClick:t[3]||(t[3]=u=>n.showingInvoices=!n.showingInvoices)},pe),a(f,{"enter-to-class":"transition-all duration-300 ease-in-out","enter-from-class":"opacity-25 max-h-0","leave-from-class":"opacity-100 max-h-xl","leave-to-class":"opacity-0 max-h-0"},{default:o(()=>[p(e("div",null,[e("ul",me,[e("li",ve,[a(l,{class:"text-white no-underline first-letter:w-full"},{default:o(()=>[i("Create Invoice")]),_:1})]),e("li",fe,[a(l,{class:"text-white no-underline first-letter:w-full",href:s.route("invoice.list")},{default:o(()=>[i("View Invoice List")]),_:1},8,["href"])])])],512),[[m,n.showingInvoices]])]),_:1})])):(h(),w("nav",we,[a(d,{href:s.route("dashboard"),active:s.route().current("dashboard")},{icon:o(()=>[ge]),default:o(()=>[i(" Dashboard ")]),_:1},8,["href","active"]),a(d,{href:"#","data-bs-toggle":"modal","data-bs-target":"#exampleModal"},{icon:o(()=>[_e]),default:o(()=>[xe]),_:1}),a(d,{href:"#","data-bs-toggle":"modal","data-bs-target":"#exampleModal"},{icon:o(()=>[ye]),default:o(()=>[ke]),_:1}),e("a",{class:"flex items-center px-6 py-2 mt-4 text-gray-100 no-underline",href:"#",onClick:t[4]||(t[4]=u=>n.showingTwoLevelMenu=!n.showingTwoLevelMenu)},Ce),a(f,{"enter-to-class":"transition-all duration-300 ease-in-out","enter-from-class":"opacity-25 max-h-0","leave-from-class":"opacity-100 max-h-xl","leave-to-class":"opacity-0 max-h-0"},{default:o(()=>[p(e("div",null,[e("ul",ze,[e("li",$e,[a(l,{class:"text-white no-underline first-letter:w-full",href:"#","data-bs-toggle":"modal","data-bs-target":"#exampleModal"},{default:o(()=>[i("Create Company")]),_:1})])])],512),[[m,n.showingTwoLevelMenu]])]),_:1}),e("a",{class:"flex items-center px-6 py-2 mt-4 text-gray-100 no-underline",href:"#",onClick:t[5]||(t[5]=u=>n.showingTwoLevelMenu2=!n.showingTwoLevelMenu2)},Ve),a(f,{"enter-to-class":"transition-all duration-300 ease-in-out","enter-from-class":"opacity-25 max-h-0","leave-from-class":"opacity-100 max-h-xl","leave-to-class":"opacity-0 max-h-0"},{default:o(()=>[p(e("div",null,[e("ul",je,[e("li",Be,[a(l,{class:"text-white no-underline first-letter:w-full",href:"#","data-bs-toggle":"modal","data-bs-target":"#exampleModal"},{default:o(()=>[i("Create Company")]),_:1})])])],512),[[m,n.showingTwoLevelMenu2]])]),_:1})]))],2),e("div",Te,[e("div",Ie,[e("div",Se,[Ee,e("div",De,[a(l,{href:"/subscription",class:"btn btn-primary","data-bs-dismiss":"modal",as:"button",type:"button"},{default:o(()=>[i("Subscription Plans")]),_:1})])])])])],64)}const Ae=j(P,[["render",Ne]]),Oe={class:"flex h-screen bg-gray-200 font-roboto"},Fe={class:"flex flex-1 flex-col overflow-hidden"},Ue={class:"flex-1 overflow-y-auto overflow-x-hidden bg-gray-200"},qe={class:"container mx-auto px-6 py-8"},Pe={class:"mb-4 text-3xl font-medium text-gray-700"},Ke={__name:"AuthenticatedLayout",setup(s){return(t,r)=>(h(),w("div",Oe,[a(Ae),e("div",Fe,[a(F),a(B),e("main",Ue,[e("div",qe,[e("h3",Pe,[v(t.$slots,"header")]),v(t.$slots,"default")])])])]))}};export{Ke as _};
