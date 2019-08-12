<template>
    <div>
        <h2>
            Add Media
        </h2>
        <hr>
        <form ref="form" @submit.prevent="addMedia()" class="mb-3" enctype="multipart/form-data">
            <div class="col-sm-6 offset-sm-3">
                <div v-if="error != ''" class="alert alert-danger">
                    <strong>Failed! </strong>{{ error }}
                </div>
                <div class="form-group">
                    <label for="type_id"><strong><small>Select Media Type: </small></strong></label>
                    <select name="type_id" id="type_id" class="form-control" required v-model="media.type_id" @change="typeChanged()" :disabled="this.is_file_disabled">
                        <option v-for="type in media_types" v-bind:key="type.id" v-bind:value="type.id">{{ type.alias }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="card card-body ">
                        <span v-html="link_or_file"></span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Title" required v-model="media.name">
                </div>
                <div class="form-group">
                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="Description" v-model="media.description"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
        <h2>
            Media
        </h2>
        <hr>

        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-3">
                        <div class="list-group" id="list-tab" role="tablist">
                            <a class="list-group-item list-group-item-action active" id="list-videos-list" data-toggle="list" href="#list-videos" role="tab" aria-controls="videos">Videos</a>
                            <a class="list-group-item list-group-item-action" id="list-images-list" data-toggle="list" href="#list-images" role="tab" aria-controls="images">Images</a>
                            <a class="list-group-item list-group-item-action" id="list-documents-list" data-toggle="list" href="#list-documents" role="tab" aria-controls="documents">Documents</a>
                            <a class="list-group-item list-group-item-action" id="list-mp3-list" data-toggle="list" href="#list-mp3" role="tab" aria-controls="mp3">MP3</a>
                            <a class="list-group-item list-group-item-action" id="list-links-list" data-toggle="list" href="#list-links" role="tab" aria-controls="links">External Links</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="list-videos" role="tabpanel" aria-labelledby="list-videos-list">
                                <h3>Videos</h3>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6 card card-body mb-2" v-for="video in videos" v-bind:key="video.id">
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <video controls="true" class="embed-responsive-item">
                                                <source v-bind:src="video.path" />
                                            </video>
                                        </div>
                                        <h5>{{ video.name }}</h5>
                                        <p><small>{{ video.description }}</small></p>
                                        <div class="btn-group">
                                            <button type="button" @click="deleteMedia(video.id)" class="btn btn-danger btn-sm">Delete</button>
                                            <button type="button" @click="editMedia(video.id)" class="btn btn-primary btn-sm">Edit</button>
                                            <button type="button" class="btn btn-default btn-sm">Favourite</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-images" role="tabpanel" aria-labelledby="list-images-list">
                                <h3>Images</h3>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6 card card-body mb-2" v-for="image in images" v-bind:key="image.id">
                                        <div class="img-responsive mb-2"><img v-bind:src="image.path" width="100%"></div>
                                        <h5>{{ image.name }}</h5>
                                        <p><small>{{ image.description }}</small></p>
                                        <div class="btn-group">
                                            <button type="button" @click="deleteMedia(image.id)" class="btn btn-danger btn-sm">Delete</button>
                                            <button type="button" @click="editMedia(image.id)" class="btn btn-primary btn-sm">Edit</button>
                                            <button type="button" class="btn btn-default btn-sm">Favourite</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-documents" role="tabpanel" aria-labelledby="list-documents-list">
                                <h3>Documents</h3>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12 card card-body mb-2" v-for="document in documents" v-bind:key="document.id">
                                        <a class="text-decoration-none" v-bind:href="document.path" target="_blank" download>
                                            <h5>{{ document.name }}</h5>
                                            <p><small>{{ document.description }}</small></p>
                                            <div class="btn-group">
                                                <button type="button" @click="deleteMedia(document.id)" class="btn btn-danger btn-sm">Delete</button>
                                                <button type="button" @click="editMedia(document.id)" class="btn btn-primary btn-sm">Edit</button>
                                                <button type="button" class="btn btn-default btn-sm">Favourite</button>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-mp3" role="tabpanel" aria-labelledby="list-mp3-list">
                                <h3>MP3</h3>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6 card card-body mb-2" v-for="audio in mp3" v-bind:key="audio.id">
                                        <div class="embed-responsive embed-responsive-21by9 mb-2">
                                            <audio controls class="embed-responsive-item">
                                                <source v-bind:src="audio.path">
                                            </audio>
                                        </div>
                                        <h5>{{ audio.name }}</h5>
                                        <p><small>{{ audio.description }}</small></p>
                                        <div class="btn-group">
                                            <button type="button" @click="deleteMedia(audio.id)" class="btn btn-danger btn-sm">Delete</button>
                                            <button type="button" @click="editMedia(audio.id)" class="btn btn-primary btn-sm">Edit</button>
                                            <button type="button" class="btn btn-default btn-sm">Favourite</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list-links" role="tabpanel" aria-labelledby="list-links-list">
                                <h3>External Links</h3>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6 card card-body mb-2" v-for="link in external_links" v-bind:key="link.id">
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item" v-bind:src="link.path" allowfullscreen></iframe>
                                        </div>
                                        <h5>{{ link.name }}</h5>
                                        <p><small>{{ link.description }}</small></p>
                                        <div class="btn-group">
                                            <button type="button" @click="deleteMedia(link.id)" class="btn btn-danger btn-sm">Delete</button>
                                            <button type="button" @click="editMedia(link.id)" class="btn btn-primary btn-sm">Edit</button>
                                            <button type="button" class="btn btn-default btn-sm">Favourite</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        data() {
            return {
                /* Fetch these details */
                media_details: [],
                videos: [],
                images: [],
                documents: [],
                mp3: [],
                external_links: [],

                media_types: [],

                media: {
                    id: 0,
                    type_id: 0,
                    path: '',
                    name: '',
                    description: '',
                    link_or_file: ''
                },
                media_id: 0,
                edit: false,
                error: '',
                is_file_disabled: false,
                link_or_file: `<input type="file" name="media_file" id="media_file" required >`
            }
        },

        created() {
            this.fetchMedia();
            this.fetchMediaTypes();
        },

        methods: {
            // Get all media
            fetchMedia() {
                let me = this;
                fetch('api/media')
                    .then(res => res.json())
                    .then(res => {
                        console.log(res);
                        me.media_details = res;
                        me.videos = res.mediaVideos;
                        me.images = res.mediaImages;
                        me.documents = res.mediaDocuments;
                        me.mp3 = res.mediaMp3;
                        me.external_links = res.mediaExternalLinks;
                    })
                    .catch(err => console.log(err));
            },

            //Get Media Types
            fetchMediaTypes() {
                fetch('api/mediatypes')
                    .then(res => res.json())
                    .then(res => {
                        console.log(res);
                        this.media_types = res;
                    });
            },

            // Change link or file html
            typeChanged() {
                let type = this.media.type_id;
                if (type == 5) {
                    this.link_or_file = `<input type="text" class="form-control" name="external_link" id="external_link" placeholder="External Link. Eg. Youtube" required v-model="${this.media.path}" :disabled="${this.is_file_disabled}">`;
                } else {
                    this.link_or_file = `<input type="file" name="media_file" id="media_file" required v-model="${this.media.path}" :disabled="${this.is_file_disabled}">`;
                }
            },

            // Delete Media By ID
            deleteMedia(id) {
                if(confirm('Are you sure?')) {
                    let me = this;
                    fetch(`api/media/${id}`, {
                        method: 'delete'
                    })
                        .then(res => res.json())
                        .then(data => {
                            if(data.status) {
                                alert('Media Deleted!');
                                me.error = '';
                                me.fetchMedia();
                            } else {
                                me.error = 'Something went wrong!';
                            }
                        })
                        .catch(err => console.log(err));
                }
            },

            // Edit Media
            editMedia(id) {
                let me = this;
                fetch(`api/media/${id}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            me.media_id = data.data.id;
                            me.edit = true;
                            me.is_file_disabled = true;
                            me.media.type_id = data.data.type_id;
                            me.media.name = data.data.name;
                            me.media.description = data.data.description;
                            me.media.path = data.data.path;
                            if (data.data.type_id == 5) {
                                me.link_or_file = `<input type="text" class="form-control" name="external_link" id="external_link" placeholder="External Link. Eg. Youtube" required v-model="${me.media.path}" disabled>`;
                            } else {
                                me.link_or_file = `<input type="file" name="media_file" id="media_file" required v-model="${me.media.path}" disabled>`;
                            }
                            me.error = '';
                        } else {
                            me.error = me.message;
                            alert(me.message);
                        }
                    })
                    .catch(err => console.log(err));
            },

            // Add new Media
            addMedia() {
                if(this.edit === false) {
                    let me = this;
                    let formdata = new FormData(me.$refs.form);
                    axios.post('api/media', formdata)
                        .then(res => res.data)
                        .then(data => {
                            console.log(data);
                            if(data.status) {
                                me.media.type_id = '';
                                me.media.name = '';
                                me.media.description = '';
                                me.media.path = '';
                                me.link_or_file = '';
                                me.error = '';
                                alert('Media Added!');
                                me.fetchMedia();
                            } else {
                                me.error = data.message;
                                alert(data.message);
                            }
                        })
                        .catch(err => console.log(err));
                } else {

                    let me = this;
                    axios.put('api/media', {
                        'id': me.media_id,
                        'name': me.media.name,
                        'description': me.media.description
                    })
                        .then(res => res.data)
                        .then(data => {
                            console.log(data);
                            if (data.status) {
                                me.edit = false;
                                me.media.type_id = '';
                                me.media.name = '';
                                me.media.description = '';
                                me.media.path = '';
                                me.link_or_file = '';
                                me.error = '';
                                me.is_file_disabled = false;
                                if (data.data.type_id == 5) {
                                    me.link_or_file = `<input type="text" class="form-control" name="external_link" id="external_link" placeholder="External Link. Eg. Youtube" required v-model="${me.media.path}">`;
                                } else {
                                    me.link_or_file = `<input type="file" name="media_file" id="media_file" required v-model="${me.media.path}">`;
                                }
                                alert('Media Updated!');
                                me.fetchMedia();
                            } else {
                                me.error = data.message;
                                alert(data.message);
                            }
                        })
                        .catch(err => console.log(err));
                }
            }
        }
    }
</script>