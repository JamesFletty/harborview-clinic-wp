/**
 * Harborview — clinician directory filter.
 *
 * Cards are server-rendered (the directory works with JS disabled). On load
 * this script fetches GET /wp-json/harborview/v1/clinicians once, indexes
 * the public payload by ID, and filters client-side by specialty and
 * "accepting new patients". If the request fails it falls back to the data-*
 * attributes already in the markup, so filtering never breaks.
 *
 * Requests are sent without cookies (credentials: 'omit'): the endpoint is
 * public and nothing identifying needs to travel with it.
 */
( () => {
	'use strict';

	const root = document.querySelector( '[data-hv-filter]' );
	if ( ! root ) {
		return;
	}

	const config = window.harborviewDirectory || {};
	const i18n = config.i18n || { count: 'Showing %d clinicians', countOne: 'Showing %d clinician' };
	const cards = Array.from( document.querySelectorAll( '[data-clinician-id]' ) );
	const chips = Array.from( root.querySelectorAll( '[data-specialty]' ) );
	const toggle = root.querySelector( '[data-accepting-toggle]' );
	const count = root.querySelector( '[data-hv-count]' );
	const empty = document.querySelector( '[data-hv-empty]' );

	const fromDom = () => new Map( cards.map( ( card ) => [
		card.dataset.clinicianId,
		{
			specialties: card.dataset.specialties.split( ' ' ).filter( Boolean ),
			accepting: card.dataset.accepting === '1',
		},
	] ) );

	let index = fromDom();
	const state = {
		specialty: new URLSearchParams( window.location.search ).get( 'specialty' ) || '',
		accepting: false,
	};

	const render = () => {
		let shown = 0;
		cards.forEach( ( card ) => {
			const record = index.get( card.dataset.clinicianId );
			const match = !! record &&
				( ! state.specialty || record.specialties.includes( state.specialty ) ) &&
				( ! state.accepting || record.accepting );
			card.hidden = ! match;
			shown += match ? 1 : 0;
		} );

		chips.forEach( ( chip ) => chip.setAttribute( 'aria-pressed', String( chip.dataset.specialty === state.specialty ) ) );
		count.textContent = ( shown === 1 ? i18n.countOne : i18n.count ).replace( '%d', String( shown ) );
		if ( empty ) {
			empty.hidden = shown > 0;
		}
	};

	chips.forEach( ( chip ) => chip.addEventListener( 'click', () => {
		state.specialty = chip.dataset.specialty;
		const url = new URL( window.location.href );
		if ( state.specialty ) {
			url.searchParams.set( 'specialty', state.specialty );
		} else {
			url.searchParams.delete( 'specialty' );
		}
		window.history.replaceState( null, '', url );
		render();
	} ) );

	toggle?.addEventListener( 'change', () => {
		state.accepting = toggle.checked;
		render();
	} );

	if ( config.endpoint ) {
		fetch( config.endpoint, { headers: { Accept: 'application/json' }, credentials: 'omit' } )
			.then( ( res ) => ( res.ok ? res.json() : Promise.reject( new Error( `HTTP ${ res.status }` ) ) ) )
			.then( ( list ) => {
				index = new Map( list.map( ( c ) => [
					String( c.id ),
					{ specialties: c.specialties.map( ( s ) => s.slug ), accepting: Boolean( c.accepting ) },
				] ) );
				root.dataset.source = 'rest';
				render();
			} )
			.catch( () => {
				root.dataset.source = 'markup';
			} );
	}

	render();
} )();
