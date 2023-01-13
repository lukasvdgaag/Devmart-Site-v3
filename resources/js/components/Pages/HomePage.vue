<template>
    <div class="landing-page h-full w-full">
        <div class="h-full w-full"
             style="background-color: #cf0063;background-image: linear-gradient(135deg, #dd1173, #350e47);">
            <div class="flex flex-col" style="height:100%">
                <div class="w-full flex flex-col items-center m-0 p-0">
                    <Navbar/>
                </div>

                <div class="flex flex-col align-center gap-0 text-center" style="height: 100%;margin-top:-80px;">
                    <h1 class="uppercase"
                        style="font-size: 100px;line-height:110px;color:#fff;font-weight: 900;">
                        DEVMART
                    </h1>
                    <div class="size-24" style="color:#fff;margin-top:4px;">Minecraft plugins, web, builds, and more.
                    </div>
                </div>
            </div>
        </div>
        <section class="flex flex-col items-center vh-50 color-light" style="background: #131128">
            <div class="d-grid">
                <div class="col-span-12 text-center mb-3">
                    <div class="mw-767px">
                        <h2 class="size-42 font-bold color-white">Go custom.</h2>
                        <p class="size-18 mt-3">Order a custom-built Minecraft plugin, website, build or Discord bot
                            from
                            GCNT</p>
                    </div>
                </div>
                <div class="col-span-12 grid d-grid-auto mt-5">
                    <div class="card home-card flex flex-col justify-center">
                        <div class="icon flex flex-col align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M512 80C512 98.01 497.7 114.6 473.6 128C444.5 144.1 401.2 155.5 351.3 158.9C347.7 157.2 343.9 155.5 340.1 153.9C300.6 137.4 248.2 128 192 128C183.7 128 175.6 128.2 167.5 128.6L166.4 128C142.3 114.6 128 98.01 128 80C128 35.82 213.1 0 320 0C426 0 512 35.82 512 80V80zM160.7 161.1C170.9 160.4 181.3 160 192 160C254.2 160 309.4 172.3 344.5 191.4C369.3 204.9 384 221.7 384 240C384 243.1 383.3 247.9 381.9 251.7C377.3 264.9 364.1 277 346.9 287.3C346.9 287.3 346.9 287.3 346.9 287.3C346.8 287.3 346.6 287.4 346.5 287.5L346.5 287.5C346.2 287.7 345.9 287.8 345.6 288C310.6 307.4 254.8 320 192 320C132.4 320 79.06 308.7 43.84 290.9C41.97 289.9 40.15 288.1 38.39 288C14.28 274.6 0 258 0 240C0 205.2 53.43 175.5 128 164.6C138.5 163 149.4 161.8 160.7 161.1L160.7 161.1zM391.9 186.6C420.2 182.2 446.1 175.2 468.1 166.1C484.4 159.3 499.5 150.9 512 140.6V176C512 195.3 495.5 213.1 468.2 226.9C453.5 234.3 435.8 240.5 415.8 245.3C415.9 243.6 416 241.8 416 240C416 218.1 405.4 200.1 391.9 186.6V186.6zM384 336C384 354 369.7 370.6 345.6 384C343.8 384.1 342 385.9 340.2 386.9C304.9 404.7 251.6 416 192 416C129.2 416 73.42 403.4 38.39 384C14.28 370.6 .0003 354 .0003 336V300.6C12.45 310.9 27.62 319.3 43.93 326.1C83.44 342.6 135.8 352 192 352C248.2 352 300.6 342.6 340.1 326.1C347.9 322.9 355.4 319.2 362.5 315.2C368.6 311.8 374.3 308 379.7 304C381.2 302.9 382.6 301.7 384 300.6L384 336zM416 278.1C434.1 273.1 452.5 268.6 468.1 262.1C484.4 255.3 499.5 246.9 512 236.6V272C512 282.5 507 293 497.1 302.9C480.8 319.2 452.1 332.6 415.8 341.3C415.9 339.6 416 337.8 416 336V278.1zM192 448C248.2 448 300.6 438.6 340.1 422.1C356.4 415.3 371.5 406.9 384 396.6V432C384 476.2 298 512 192 512C85.96 512 .0003 476.2 .0003 432V396.6C12.45 406.9 27.62 415.3 43.93 422.1C83.44 438.6 135.8 448 192 448z"/>
                            </svg>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">Starting from 12 EUR/hour</h3>
                        <p class="size-16 mt-3">Custom development rates range from 12 to 16 EUR per hour and are hourly
                            charged.</p>
                    </div>
                    <div class="card home-card">
                        <div class="icon flex flex-col align-center">
                            <font-awesome-icon :icon="['fab', 'github']" size="6x"></font-awesome-icon>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">Get the source</h3>
                        <p class="size-16 mt-3">Get the source of your project at no additional costs, provided through
                            GitHub.</p>
                    </div>
                    <div class="card home-card">
                        <div class="icon flex flex-col align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 64C238.3 64 224 78.33 224 96C224 113.7 238.3 128 256 128C273.7 128 288 113.7 288 96C288 78.33 273.7 64 256 64zM256 416C291.3 416 320 387.3 320 352C320 334.6 313.1 318.9 301.9 307.4L365.1 161.7C371.3 149.5 365.8 135.4 353.7 130C341.5 124.7 327.4 130.2 322 142.3L257.9 288C257.3 288 256.6 287.1 256 287.1C220.7 287.1 192 316.7 192 352C192 387.3 220.7 416 256 416V416zM144 112C126.3 112 112 126.3 112 144C112 161.7 126.3 176 144 176C161.7 176 176 161.7 176 144C176 126.3 161.7 112 144 112zM96 288C113.7 288 128 273.7 128 256C128 238.3 113.7 224 96 224C78.33 224 64 238.3 64 256C64 273.7 78.33 288 96 288zM416 224C398.3 224 384 238.3 384 256C384 273.7 398.3 288 416 288C433.7 288 448 273.7 448 256C448 238.3 433.7 224 416 224z"/>
                            </svg>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">Best performance</h3>
                        <p class="size-16 mt-3">Expect only max performance of our products and nothing less.</p>
                    </div>
                    <div class="card home-card">
                        <div class="icon flex flex-col align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/>
                            </svg>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">Reputable</h3>
                        <p class="size-16 mt-3">Our products have sold over 120.000 times with an average rating of 4.6
                            stars and are well maintained.</p>
                    </div>
                    <div class="card home-card">
                        <div class="icon flex flex-col align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M414.8 40.79L286.8 488.8C281.9 505.8 264.2 515.6 247.2 510.8C230.2 505.9 220.4 488.2 225.2 471.2L353.2 23.21C358.1 6.216 375.8-3.624 392.8 1.232C409.8 6.087 419.6 23.8 414.8 40.79H414.8zM518.6 121.4L630.6 233.4C643.1 245.9 643.1 266.1 630.6 278.6L518.6 390.6C506.1 403.1 485.9 403.1 473.4 390.6C460.9 378.1 460.9 357.9 473.4 345.4L562.7 256L473.4 166.6C460.9 154.1 460.9 133.9 473.4 121.4C485.9 108.9 506.1 108.9 518.6 121.4V121.4zM166.6 166.6L77.25 256L166.6 345.4C179.1 357.9 179.1 378.1 166.6 390.6C154.1 403.1 133.9 403.1 121.4 390.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4L121.4 121.4C133.9 108.9 154.1 108.9 166.6 121.4C179.1 133.9 179.1 154.1 166.6 166.6V166.6z"/>
                            </svg>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">What we code</h3>
                        <p class="size-16 mt-3">Java, JavaScript, NodeJS, PHP, HTML, CSS, SQL, MongoDB, Redis.</p>
                    </div>
                    <div class="card home-card">
                        <div class="icon flex flex-col align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                <path d="M0 383.9l64 .0404c17.75 0 32-14.29 32-32.03V128.3L0 128.3V383.9zM48 320.1c8.75 0 16 7.118 16 15.99c0 8.742-7.25 15.99-16 15.99S32 344.8 32 336.1C32 327.2 39.25 320.1 48 320.1zM348.8 64c-7.941 0-15.66 2.969-21.52 8.328L228.9 162.3C228.8 162.5 228.8 162.7 228.6 162.7C212 178.3 212.3 203.2 226.5 218.7c12.75 13.1 39.38 17.62 56.13 2.75C282.8 221.3 282.9 221.3 283 221.2l79.88-73.1c6.5-5.871 16.75-5.496 22.62 1c6 6.496 5.5 16.62-1 22.62l-26.12 23.87L504 313.7c2.875 2.496 5.5 4.996 7.875 7.742V127.1c-40.98-40.96-96.48-63.88-154.4-63.88L348.8 64zM334.6 217.4l-30 27.49c-29.75 27.11-75.25 24.49-101.8-4.371C176 211.2 178.1 165.7 207.3 138.9L289.1 64H282.5C224.7 64 169.1 87.08 128.2 127.9L128 351.8l18.25 .0369l90.5 81.82c27.5 22.37 67.75 18.12 90-9.246l18.12 15.24c15.88 12.1 39.38 10.5 52.38-5.371l31.38-38.6l5.374 4.498c13.75 11 33.88 9.002 45-4.748l9.538-11.78c11.12-13.75 9.036-33.78-4.694-44.93L334.6 217.4zM544 128.4v223.6c0 17.62 14.25 32.05 31.1 32.05L640 384V128.1L544 128.4zM592 352c-8.75 0-16-7.246-16-15.99c0-8.875 7.25-15.99 16-15.99S608 327.2 608 336.1C608 344.8 600.8 352 592 352z"/>
                            </svg>
                        </div>
                        <h3 class="size-18 font-bold uppercase mt-4 color-white">Fast support</h3>
                        <p class="size-16 mt-3">Experiencing any issues with our products or have any questions? Don't
                            hesitate to contact us on our Discord.</p>
                    </div>
                </div>

                <div class="col-span-12 flex flex-col align-center pt-5">
                    <a href="/discord"
                       target="_blank"
                       class="block button pink box-shadow p-3 plain mw-100 size-20 uppercase font-bold">
                        Let's do this!
                    </a>
                </div>
            </div>
        </section>
        <section class="flex flex-col align-center color-light" style="background: #33335d">
            <div class="d-grid">
                <div class="col-span-12 flex items-center flex-row">

                    <div class="w-full h-full flex flex-col align-center">
                        <img style="max-height: 80%" src="/assets/img/plugins-preview2.png" alt="Plugins preview">
                    </div>
                    <div class="w-full text-center flex flex-col align-center">
                        <div class="mw-767px">
                            <h2 class="size-42 font-bold color-white">Minecraft Plugins.</h2>
                            <p class="size-18 mt-3">Professional free and premium Minecraft Server Plugins for everyone.
                                GCNT is the creator of popular projects such as SkyWarsReloaded, AdditionsPlus, and
                                NoChatReports.</p>

                            <a href="/plugins"
                               class="block mt-4 fit-content button pink box-shadow p-3 plain size-16 uppercase font-bold">
                                Show Me Plugins
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar";

export default {
    name: "HomeView",
    components: {Navbar}
}
</script>

<style scoped>

</style>
