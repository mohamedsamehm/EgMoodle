<template>
  <b-card
    no-body
    class="card-statistics"
  >
    <b-card-body class="statistics-body">
      <b-row>
        <b-col xl="6" sm="6">
          <b-media no-body>
            <b-media-body class="my-auto explore-community">
              <h1 class="font-weight-bolder mb-0">
                Explore Now <div><span class="kidesign-color">KI</span> Community</div>
              </h1>
              <b-card-text class="font-small-3 mb-0">
                Lorem ipsum do lor sit amet, consectetur adipiscing elit, sed do eiusmod tempor inci didunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
              </b-card-text>
							<div class="demo-center-spacing">
								<b-button size="lg" variant="dark" :to="{ name: 'live-search' }">
									<feather-icon
										icon="SearchIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Live Search</span>
								</b-button>
								<b-button
									size="lg"
									variant="dark"
									href="#"
								>
									<feather-icon
										icon="VideoIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Video Search</span>
								</b-button>
								<b-button
									size="lg"
									variant="dark"
									href="#"
								>
									<feather-icon
										icon="MessageSquareIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Vape Search</span>
								</b-button>
								<b-button
									size="lg"
									variant="dark"
									href="#"
								>
									<feather-icon
										icon="MapIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Geo Search</span>
								</b-button>			

								<b-button
									size="lg"
									variant="dark"
									:to="{ name: 'data' }"
								>
									<feather-icon
										icon="GlobeIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Data</span>
								</b-button>		
								<b-button
									size="lg"
									variant="dark"
									:to="{ name: 'build' }"
								>
									<font-awesome-icon icon="fa-solid fa-arrow-down-wide-short" />
									<span class="align-middle">Build</span>
								</b-button>		

								<!--
								<b-button
									size="lg"
									variant="dark"
									@click="fetchJournalists"
								>
									<feather-icon
										icon="GlobeIcon"
										class="mr-50 align-middle"
									/>
									<span class="align-middle">Journalist</span>
								</b-button>		
								-->
								
							</div>
            </b-media-body>
          </b-media>
        </b-col>
        <b-col xl="6" sm="6" class="text-center">
					<b-img :src="require('@/assets/images/home.png')" alt="Main image"/>
        </b-col>
      </b-row>
    </b-card-body>
  </b-card>
</template>

<script>

import {
  BCard, BButton, BCardHeader, BCardTitle, BCardText, BCardBody, BImg, BRow, BCol, BMedia, BMediaAside, BAvatar, BMediaBody,
} from 'bootstrap-vue';
import axios from '@axios';

export default {
  components: {
		BImg,
    BRow,
    BCol,
    BCard,
		BButton,
    BCardHeader,
    BCardTitle,
    BCardText,
    BCardBody,
    BMedia,
    BAvatar,
    BMediaAside,
    BMediaBody,
  },
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    fetchJournalists() {
			console.log('fetchJournalists');
      return new Promise((resolve, reject) => {
        axios
          .get('/api/journalists')
          .then(res => {
						// resolve(response)
						console.log('res ', res)
					})
          .catch(error => reject(error))
      })
    },

  },	
}
</script>

<style scoped>
	p {
		/* color: rgba(3,7,46,.71); */
		font-size: 16px !important;
		line-height: 24px;
		margin-bottom: 40px !important;
		max-width: 500px;
	}
</style>