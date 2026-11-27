/**
 * Harborview — site behaviour. Vanilla, no dependencies.
 *  1. Mobile drawer (aria-expanded, Escape to close, focus management).
 *  2. Header hairline after scroll.
 *  3. Accessible accordion (WAI-ARIA APG pattern). Panels use
 *     hidden="until-found" so browser find-in-page still reaches answers.
 *  4. Reveal-on-scroll, skipped for reduced motion.
 */
( () => {
	'use strict';

	const root = document.documentElement;
	root.classList.add( 'hv-js' );

	/* 1. Drawer */
	const toggle = document.querySelector( '[data-hv-nav-toggle]' );
	const drawer = document.querySelector( '[data-hv-drawer]' );
	if ( toggle && drawer ) {
		const label = toggle.querySelector( '.screen-reader-text' );
		const setOpen = ( open ) => {
			toggle.setAttribute( 'aria-expanded', String( open ) );
			drawer.hidden = ! open;
			if ( label ) {
				label.textContent = open ? 'Close menu' : 'Open menu';
			}
			if ( open ) {
				drawer.querySelector( 'a' )?.focus();
			}
		};
		toggle.addEventListener( 'click', () => setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' ) );
		drawer.addEventListener( 'click', ( e ) => e.target.closest( 'a[href^="#"], a[href*="#"]' ) && setOpen( false ) );
		document.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'Escape' && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				setOpen( false );
				toggle.focus();
			}
		} );
	}

	/* 2. Header state */
	const header = document.querySelector( '[data-hv-header]' );
	if ( header ) {
		const onScroll = () => header.classList.toggle( 'is-scrolled', window.scrollY > 8 );
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	/* 3. Accordion */
	document.querySelectorAll( '[data-hv-accordion]' ).forEach( ( accordion ) => {
		const triggers = Array.from( accordion.querySelectorAll( '.hv-accordion__trigger' ) );

		const set = ( trigger, open ) => {
			const panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
			trigger.setAttribute( 'aria-expanded', String( open ) );
			if ( open ) {
				panel.removeAttribute( 'hidden' );
			} else {
				panel.setAttribute( 'hidden', 'until-found' );
			}
		};

		// Server renders everything open (works without JS); collapse all but the first.
		triggers.forEach( ( trigger, i ) => {
			set( trigger, i === 0 );
			trigger.addEventListener( 'click', () => set( trigger, trigger.getAttribute( 'aria-expanded' ) !== 'true' ) );

			const panel = document.getElementById( trigger.getAttribute( 'aria-controls' ) );
			panel.addEventListener( 'beforematch', () => set( trigger, true ) );
		} );

		// Arrow / Home / End navigation between headers.
		accordion.addEventListener( 'keydown', ( e ) => {
			const index = triggers.indexOf( document.activeElement );
			if ( index < 0 ) {
				return;
			}
			const map = { ArrowDown: index + 1, ArrowUp: index - 1, Home: 0, End: triggers.length - 1 };
			if ( e.key in map ) {
				e.preventDefault();
				triggers[ ( map[ e.key ] + triggers.length ) % triggers.length ].focus();
			}
		} );
	} );

	/* 4. Reveal */
	const items = document.querySelectorAll( '[data-hv-reveal]' );
	if ( ! ( 'IntersectionObserver' in window ) || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		items.forEach( ( el ) => el.classList.add( 'is-revealed' ) );
		return;
	}
	const io = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			if ( entry.isIntersecting ) {
				entry.target.classList.add( 'is-revealed' );
				io.unobserve( entry.target );
			}
		} );
	}, { rootMargin: '0px 0px -6% 0px', threshold: 0.1 } );
	items.forEach( ( el, i ) => {
		el.style.transitionDelay = `${ ( i % 2 ) * 90 }ms`;
		io.observe( el );
	} );
} )();
