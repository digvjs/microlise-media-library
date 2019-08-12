<?php

namespace Microlise\Http\Controllers;

use Illuminate\Support\Facades\File;
use Microlise\MediaMeta;
use Microlise\MediaType;
use Validator;
use Microlise\Http\Resources\MediaResource;
use Microlise\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Media
        $media = Media::with('mediaMeta', 'mediaType')->get();

        $mediaVideos = Media::where('type_id', MediaType::VIDEO)->with('mediaMeta')->get();
        $mediaImages = Media::where('type_id', MediaType::IMAGES)->with('mediaMeta')->get();
        $mediaDocuments = Media::where('type_id', MediaType::DOCUMENTS)->with('mediaMeta')->get();
        $mediaMp3 = Media::where('type_id', MediaType::MP3)->with('mediaMeta')->get();
        $mediaExternalLinks = Media::where('type_id', MediaType::LINKS)->with('mediaMeta')->get();

        // Return collection of Media as Resource
        return response()->json([
            'mediaVideos' => $mediaVideos,
            'mediaImages' => $mediaImages,
            'mediaDocuments' => $mediaDocuments,
            'mediaMp3' => $mediaMp3,
            'mediaExternalLinks' => $mediaExternalLinks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequestData($request);

        if (!$validator['status']) {
            return response()->json([
                'status' => false,
                'message' => $validator['message']
            ]);
        }

        // Successfully file validated
        $mediaType = (int)$request->input('type_id');

        try {

            if ($mediaType == MediaType::LINKS) {
                $request['path'] = $request->external_link;
            } else {
                // Upload file
                $file = $request->media_file;
                $mimeType = $file->getClientMimeType();
                $extention = $file->getClientOriginalExtension();
                $size = $file->getClientSize();
                $filename = $file->getClientOriginalName();

                $destinationPath = 'upload/media/' . $request->input('type_id');
                if(!File::isDirectory($destinationPath)){
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $request['path'] = $file->move($destinationPath, time() . '-' . $filename);
            }

            // Save Media details in DB
            $media = Media::create($request->all());

            //Save Media Meta Data
            if (isset($request->media_file)) {
                $mediaMeta = new MediaMeta();
                $mediaMeta->media_id = $media->id;
                $mediaMeta->size = $size;
                $mediaMeta->mime_type = $mimeType;
                $mediaMeta->extention = $extention;
                $mediaMeta->save();
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Microlise\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media, $mediaId)
    {
        try {
            // Get Media by ID
            $media = Media::findOrFail((int)$mediaId);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $media
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Microlise\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Microlise\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        $validator = Validator::make($request->all(), [
           'id' => 'required',
           'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->first()
            ]);
        }

        try {
            $media = Media::findOrFail($request->id);
            $media->name = $request->name;
            $media->description = $request->description;
            $media->save();

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $media
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Microlise\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media, $mediaId)
    {
        Media::destroy((int)$mediaId);

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * @param $request
     * @return array
     */
    private function validateRequestData($request)
    {
        $validator = Validator::make($request->all(), ['type_id' => 'required', 'name' => 'required']);

        if($validator->fails()) {
            return array('status' => false, 'message' => $validator->messages()->first());
        }

        $mediaType = (int)$request->input('type_id');

        if ($mediaType == MediaType::LINKS) {
            $typeValidator = Validator::make($request->all(), ['external_link' => 'required']);
        } else {
            // Validation rules based on memes
            $customMessages = [
                'max' => 'Maximum file size allowed is 5MB.'
            ];

            if ($mediaType == MediaType::VIDEO) {
                $typeValidator = Validator::make($request->all(), ['media_file' => 'required|file|max:5120|mimes:mp4,avi,mkv,3gp,mov,wmv,webm'], $customMessages);
            } elseif ($mediaType == MediaType::IMAGES) {
                $typeValidator = Validator::make($request->all(), ['media_file' => 'required|file|max:5120|mimes:jpeg,jpg,png,gif,bmp,tiff'], $customMessages);
            } elseif ($mediaType == MediaType::DOCUMENTS) {
                $typeValidator = Validator::make($request->all(), ['media_file' => 'required|file|max:5120|mimes:doc,docx,pdf,xls,xlsx,ppt,pptx'], $customMessages);
            } elseif ($mediaType == MediaType::MP3) {
                $typeValidator = Validator::make($request->all(), ['media_file.*' => 'required|file|max:5120|mimes:mp3'], $customMessages);
            } else {
                return array('status' => false, 'message' => 'Invalid media type selected!');
            }
        }

        if($typeValidator->fails()) {
            return array('status' => false, 'message' => $typeValidator->messages()->first());
        }

        return array('status' => true);
    }

}
