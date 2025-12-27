<script setup>
import { Head } from '@inertiajs/vue3';
import PublicHeader from '@/Components/PublicHeader.vue';
import PublicFooter from '@/Components/PublicFooter.vue';
</script>

<template>
    <Head title="Terms of Service - ITrove Bills" />

    <div class="min-h-screen bg-gray-50 flex flex-col">
        <PublicHeader />

        <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-white text-center">Terms of Service</h1>
                </div>
                
                <div class="p-8 md:p-12 prose prose-lg max-w-none text-gray-600">
                    <p class="lead">Last updated: December 27, 2024</p>
                    
                    <h3>1. Terms</h3>
                    <p>By accessing this Website, accessible from https://itrovebills.com/, you are agreeing to be bound by these Website Terms and Conditions of Use and agree that you are responsible for the agreement with any applicable local laws. If you disagree with any of these terms, you are prohibited from accessing this site. The materials contained in this Website are protected by copyright and trade mark law.</p>

                    <h3>2. Use License</h3>
                    <p>Permission is granted to temporarily download one copy of the materials on ITrove Bills's Website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                    <ul>
                        <li>modify or copy the materials;</li>
                        <li>use the materials for any commercial purpose or for any public display;</li>
                        <li>attempt to reverse engineer any software contained on ITrove Bills's Website;</li>
                        <li>remove any copyright or other proprietary notations from the materials; or</li>
                        <li>transfer the materials to another person or "mirror" the materials on any other server.</li>
                    </ul>
                    <p>This will let ITrove Bills to terminate upon violations of any of these restrictions. Upon termination, your viewing right will also be terminated and you should destroy any downloaded materials in your possession whether it is printed or electronic format.</p>

                    <h3>3. Disclaimer</h3>
                    <p>All the materials on ITrove Bills's Website are provided "as is". ITrove Bills makes no warranties, may it be expressed or implied, therefore negates all other warranties. Furthermore, ITrove Bills does not make any representations concerning the accuracy or likely results of the use of the materials on its Website or otherwise relating to such materials or on any sites linked to this Website.</p>

                    <h3>4. Limitations</h3>
                    <p>ITrove Bills or its suppliers will not be hold accountable for any damages that will arise with the use or inability to use the materials on ITrove Bills's Website, even if ITrove Bills or an authorize representative of this Website has been notified, orally or written, of the possibility of such damage. Some jurisdiction does not allow limitations on implied warranties or limitations of liability for incidental damages, these limitations may not apply to you.</p>

                    <h3>5. Revisions and Errata</h3>
                    <p>The materials appearing on ITrove Bills's Website may include technical, typographical, or photographic errors. ITrove Bills will not promise that any of the materials in this Website are accurate, complete, or current. ITrove Bills may change the materials contained on its Website at any time without notice. ITrove Bills does not make any commitment to update the materials.</p>

                    <h3>6. Links</h3>
                    <p>ITrove Bills has not reviewed all of the sites linked to its Website and is not responsible for the contents of any such linked site. The presence of any link does not imply endorsement by ITrove Bills of the site. The use of any linked website is at the user's own risk.</p>

                    <h3>7. Site Terms of Use Modifications</h3>
                    <p>ITrove Bills may revise these Terms of Use for its Website at any time without prior notice. By using this Website, you are agreeing to be bound by the current version of these Terms and Conditions of Use.</p>

                    <h3>8. Governing Law</h3>
                    <p>Any claim related to ITrove Bills's Website shall be governed by the laws of in without regards to its conflict of law provisions.</p>
                </div>
            </div>
        </main>

        <PublicFooter />
    </div>
</template>
