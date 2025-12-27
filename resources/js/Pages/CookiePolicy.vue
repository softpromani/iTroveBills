<script setup>
import { Head } from '@inertiajs/vue3';
import PublicHeader from '@/Components/PublicHeader.vue';
import PublicFooter from '@/Components/PublicFooter.vue';
</script>

<template>
    <Head title="Cookie Policy - ITrove Bills" />

    <div class="min-h-screen bg-gray-50 flex flex-col">
        <PublicHeader />

        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-white text-center">Cookie Policy</h1>
                </div>
                
                <div class="p-8 md:p-12 prose prose-lg max-w-none text-gray-600">
                    <p class="lead">Last updated: December 27, 2024</p>
                    
                    <p>This is the Cookie Policy for ITrove Bills, accessible from https://itrovebills.com/</p>

                    <h3>What Are Cookies</h3>
                    <p>As is common practice with almost all professional websites this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or 'break' certain elements of the sites functionality.</p>

                    <h3>How We Use Cookies</h3>
                    <p>We use cookies for a variety of reasons detailed below. Unfortunately in most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.</p>

                    <h3>Disabling Cookies</h3>
                    <p>You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of the this site. Therefore it is recommended that you do not disable cookies.</p>

                    <h3>The Cookies We Set</h3>
                    <ul>
                        <li>
                            <strong>Account related cookies:</strong> If you create an account with us then we will use cookies for the management of the signup process and general administration. These cookies will usually be deleted when you log out however in some cases they may remain afterwards to remember your site preferences when logged out.
                        </li>
                        <li>
                            <strong>Login related cookies:</strong> We use cookies when you are logged in so that we can remember this fact. This prevents you from having to log in every single time you visit a new page. These cookies are typically removed or cleared when you log out to ensure that you can only access restricted features and areas when logged in.
                        </li>
                        <li>
                            <strong>Site preferences cookies:</strong> In order to provide you with a great experience on this site we provide the functionality to set your preferences for how this site runs when you use it. In order to remember your preferences we need to set cookies so that this information can be called whenever you interact with a page is affected by your preferences.
                        </li>
                    </ul>

                    <h3>Third Party Cookies</h3>
                    <p>In some special cases we also use cookies provided by trusted third parties. The following section details which third party cookies you might encounter through this site.</p>
                    <ul>
                        <li>
                            This site uses Google Analytics which is one of the most widespread and trusted analytics solution on the web for helping us to understand how you use the site and ways that we can improve your experience. These cookies may track things such as how long you spend on the site and the pages that you visit so we can continue to produce engaging content.
                        </li>
                    </ul>

                    <h3>More Information</h3>
                    <p>Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site.</p>
                    <p>For more general information on cookies, please read usage policies associated with cookies.</p>
                    <p>However if you are still looking for more information then you can contact us through one of our preferred contact methods:</p>
                    <ul>
                        <li>Email: info@innovationtrove.in</li>
                    </ul>
                </div>
            </div>
        </main>

        <PublicFooter />
    </div>
</template>
