<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
	<div class="flex justify-between items-center h-fit w-full p-4 bg-gray-100">
		<Link :href="route('home')">LOGO</Link>

		<div class="flex justify-between items-center gap-12">
			<div class="flex justify-end items-center gap-8">
                <NavLink
                    v-for="(item, index) in usePage().props.navigationItems"
                    :key="'responsive-menu-item-' + index"
                    :href="item.href"
                    :active="item.current"
                >
                    {{ item.label }}
                </NavLink>
			</div>

			<language-switcher />
		</div>
	</div>
</template>
