<script setup>

</script>

<template>
    <h1>LandingPage Layout</h1>
    <div>
        <header>
            <nav>
                <div class="space-x-6">


                    <Link :href="route('login')" class="nav-link">Log In
                    </Link>

                </div>

            </nav>
        </header>
        <main class="p-4">
            <slot />
        </main>
    </div>
</template>