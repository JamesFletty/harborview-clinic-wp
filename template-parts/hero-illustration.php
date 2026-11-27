<?php
/**
 * Hero illustration: an arched window onto the harbor. Inline SVG, no request.
 *
 * @package Harborview
 */

defined( 'ABSPATH' ) || exit;
?>
<svg class="hv-hero__art" viewBox="0 0 560 640" aria-hidden="true" focusable="false">
	<defs>
		<clipPath id="hv-arch"><path d="M0 640V280a280 280 0 0 1 560 0v360z"/></clipPath>
		<linearGradient id="hv-sky" x1="0" y1="0" x2="0" y2="1">
			<stop offset="0" stop-color="#E9EEEA"/>
			<stop offset="0.55" stop-color="#F3E9DE"/>
			<stop offset="1" stop-color="#EED9C8"/>
		</linearGradient>
		<linearGradient id="hv-sea" x1="0" y1="0" x2="0" y2="1">
			<stop offset="0" stop-color="#2C4A43"/>
			<stop offset="1" stop-color="#1D332E"/>
		</linearGradient>
	</defs>
	<g clip-path="url(#hv-arch)">
		<rect width="560" height="640" fill="url(#hv-sky)"/>
		<circle cx="372" cy="292" r="58" fill="#B86B4B"/>
		<path d="M0 392c60-26 120-40 186-30s104 22 160 8 110-34 214-22v120H0z" fill="#A9BCB3"/>
		<path d="M0 430c70-30 136-32 196-14s120 14 182-6 120-22 182-4v94H0z" fill="#6E8B82"/>
		<rect x="118" y="352" width="10" height="44" fill="#F8F7F4"/>
		<path d="M116 352h14l-7-14z" fill="#1D332E"/>
		<rect x="118" y="366" width="10" height="6" fill="#B86B4B"/>
		<rect y="468" width="560" height="172" fill="url(#hv-sea)"/>
		<g fill="none" stroke="#F8F7F4" stroke-linecap="round" stroke-opacity=".38" stroke-width="2">
			<path d="M40 500c14-6 28-6 42 0s28 6 42 0"/>
			<path d="M300 512c14-6 28-6 42 0s28 6 42 0 28-6 42 0"/>
			<path d="M150 548c14-6 28-6 42 0s28 6 42 0"/>
			<path d="M380 582c14-6 28-6 42 0s28 6 42 0"/>
			<path d="M60 600c14-6 28-6 42 0s28 6 42 0 28-6 42 0"/>
		</g>
		<path d="M330 468c20 6 64 6 84 0" stroke="#D9A486" stroke-opacity=".55" stroke-width="3" stroke-linecap="round"/>
		<path d="M346 486c14 4 38 4 52 0" stroke="#D9A486" stroke-opacity=".35" stroke-width="3" stroke-linecap="round"/>
	</g>
	<path d="M1 639V280a279 279 0 0 1 558 0v359" fill="none" stroke="#1D332E" stroke-opacity=".18"/>
</svg>
