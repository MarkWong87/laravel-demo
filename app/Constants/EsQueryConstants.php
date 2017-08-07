<?php

/**
 * Created by PhpStorm.
 * User: markWong
 * Date: 2017/3/9
 * Time: 下午4:55
 */
namespace App\Constants;
class EsQueryConstants
{
    const ES_QUERY_INDEX_NAME = "query";
    const ES_QUERY_ALBUM_INDEX_NAME = "query_album";
    const ES_QUERY_USER_INDEX_NAME = "query_user";
    const ES_QUERY_BANGUMI_INDEX_NAME = "query_bangumi";
    const ES_CONTRIBUTION_QUERY_TYPE_NAME = "contribution";
    const ES_BANGUMI_QUERY_TYPE_NAME = "bangumi";
    const ES_ALBUM_QUERY_TYPE_NAME = "album";
    const ES_ALBUM_CONTENT_QUERY_TYPE_NAME = "album_content";
    const ES_USER_QUERY_TYPE_NAME = "user";
    const ES_QUERY_LIVE_INDEX_NAME = "query_live";
    const ES_COMPERE_QUERY_TYPE_NAME_COMPERE = "compere";

    const ES_QUERY_RESULT_TOOK_KEY = "took";
    const ES_QUERY_RESULT_TOTAL_KEY = "total";
    const ES_QUERY_RESULT_HITS_KEY = "hits";
    const ES_QUERY_RESULT_PAGE_KEY = "pageNo";

    const ES_QUERY_FIELD_NAME_ID = "id";
    const ES_QUERY_FIELD_NAME_CHANNEL_ID = "channel_id";
    const ES_QUERY_FIELD_NAME_PARENT_CHANNEL_ID = "parent_channel_id";
    const ES_QUERY_FIELD_NAME_TYPE_ID = "type_id";
    const ES_QUERY_FIELD_NAME_BANANA_COUNT = "banana_count";
    const ES_QUERY_FIELD_NAME_FAVORITE_COUNT = "favorite_count";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_DANMU_SIZE_LONG_NAME = "content_list.danmu_size";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_INDEX_LONG_NAME = "content_list.index";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_ID_LONG_NAME = "content_list.id";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_DANMU_SIZE = "danmu_size";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_DURATION = "duration";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_INDEX = "index";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST_ID = "id";
    const ES_QUERY_FIELD_NAME_VIDEO_ID = "video_id";
    const ES_QUERY_FIELD_NAME_IS_TUDOU_DOMAIN = "is_tudou_domain";
    const ES_QUERY_FIELD_NAME_CONTRIBUTE_TIME = "contribute_time";
    const ES_QUERY_FIELD_NAME_TITLE = "title";
    const ES_QUERY_FIELD_NAME_LINK = "link";
    const ES_QUERY_FIELD_NAME_DESCRIPTION = "description";
    const ES_QUERY_FIELD_NAME_IS_ESSENSE = "is_essense";
    const ES_QUERY_FIELD_NAME_IS_RECOMMENDED = "is_recommended";
    const ES_QUERY_FIELD_NAME_IS_TOP_LEVEL = "is_top_level";
    const ES_QUERY_FIELD_NAME_CONTENT_LIST = "content_list";
    const ES_QUERY_FIELD_NAME_VIEW_COUNT = "view_count";
    const ES_QUERY_FIELD_NAME_COVER_IMAGE = "cover_image";
    const ES_QUERY_FIELD_NAME_COMMENT_COUNT = "comment_count";
    const ES_QUERY_FIELD_NAME_USERNAME = "username";
    const ES_QUERY_FIELD_NAME_USER_ID = "user_id";
    const ES_QUERY_FIELD_NAME_LATEST_ACTIVE_TIME = "latest_active_time";
    const ES_QUERY_FIELD_NAME_LATEST_COMMENT_TIME = "latest_comment_time";
    const ES_QUERY_FIELD_NAME_LATEST_DANMU_TIME = "latest_danmu_time";
    const ES_QUERY_FIELD_NAME_LATEST_COUNT_TIME = "latest_count_time";
    const ES_QUERY_FIELD_NAME_CHANNEL_PATH = "channel_path";
    const ES_QUERY_FIELD_NAME_STATUS = "status";
    const ES_QUERY_FIELD_NAME_DISPLAY = "display";
    const ES_QUERY_FIELD_NAME_APPSTORE_VERIFIED = "AppStore_verified";
    const ES_QUERY_FIELD_NAME_USER_AVATAR = "user_avatar";
    const ES_QUERY_FIELD_NAME_IS_ARTICLE = "is_article";
    const ES_QUERY_FIELD_NAME_TAG_LIST = "tag_list";
    const ES_QUERY_FIELD_NAME_TAG_LIST_ID = "tag_list.id";
    const ES_QUERY_FIELD_NAME_TAG_LIST_NAME = "name";
    const ES_QUERY_FIELD_NAME_BIG_COVER_IMAGE = "big_cover_image";
    const ES_QUERY_FIELD_NAME_IS_VIEW_ONLY = "is_view_only";


        //08.02
    const ES_QUERY_FIELD_NAME_CONTENT_ID = "content_id";
    const ES_QUERY_FIELD_NAME_GROUP_NAME_LIST_GROUPID = "group_name_list.groupId";
    const ES_QUERY_FIELD_NAME_GROUP_NAME_LIST_ID = "group_name_list.id";
    const ES_QUERY_FIELD_NAME_UPDATE_TIME = "update_time";
    const ES_QUERY_FIELD_NAME_ALBUM_ID = "album_id";
    const ES_QUERY_FIELD_NAME_GROUP_ID = "group_id";
    const ES_QUERY_FIELD_NAME_SORT = "sort";
    const ES_QUERY_FIELD_NAME_SYS_SORT = "sys_sort";
    const ES_QUERY_FIELD_NAME_COVER = "cover";
    const ES_QUERY_FIELD_NAME_CONTENT_SIZE = "content_size";
    const ES_QUERY_FIELD_NAME_STOW_COUNT = "stow_count";
    const ES_QUERY_FIELD_NAME_DANMU_SIZE = "danmu_size";
    const ES_QUERY_FIELD_NAME_GROUP_NAME = "group_name";
    const ES_QUERY_FIELD_NAME_ALBUM_TITLE = "album_title";
    const ES_QUERY_FIELD_NAME_ALBUM_CONTENT_TITLE = "album_content_title";
    const ES_QUERY_FIELD_NAME_COVER_IMG = "cover_img";
    const ES_QUERY_FIELD_NAME_SIGNATURE = "signature";
    const ES_QUERY_FIELD_NAME_CONTRIBUTION_COUNT = "contribution_count";
    const ES_QUERY_FIELD_NAME_FANS_COUNT = "fans_count";
    const ES_QUERY_FIELD_NAME_CHANNEL_NAME = "channel_name";
    const ES_QUERY_FIELD_NAME_PARENT_CHANNEL_NAME = "parent_channel_name";
    const ES_QUERY_FIELD_NAME_VIDEO_LIST = "video_list";
    const ES_QUERY_FIELD_NAME_SOURCE_TYPE = "source_type";
    const ES_QUERY_FIELD_NAME_SOURCE_ID = "source_id";
    const ES_QUERY_FIELD_NAME_IS_CHECKED = "is_checked";//是否审核过
    const ES_QUERY_FIELD_NAME_IS_ALLOWED_ADD_TAG = "is_allowed_add_tag";//是否允许添加标签
    const ES_QUERY_LIVE_FIELD_FOLLOWED = "followed";

        //12.15番剧
    const ES_QUERY_FIELD_DISPLAY_WEB = "display_web";
    const ES_QUERY_FIELD_NAME_INTRO = "intro";
    const ES_QUERY_FIELD_NAME_TYPE_NAME = "type_name";
    const ES_QUERY_FIELD_NAME_LAST_UPDATE_TIME = "last_update_time";
    const ES_QUERY_FIELD_NAME_YEAR = "year";
    const ES_QUERY_FIELD_NAME_WEEK = "week";
    const ES_QUERY_FIELD_NAME_BANGUMI_TYPES_LIST = "bangumi_types_list";
    const ES_QUERY_FIELD_NAME_BANGUMI_TYPE_ID = "bangumi_type_id";
    const ES_QUERY_FIELD_NAME_BANGUMI_TYPE_NAME = "bangumi_type_name";
    const ES_QUERY_FIELD_NAME_VIDEO_TITLE = "video_title";
    const ES_QUERY_FIELD_NAME_RECOMMEND_LIST = "recommend_list";
    const ES_QUERY_FIELD_NAME_RECOMMEND_BANGUMI_ID = "recommend_bangumi_id";
    const ES_QUERY_FIELD_NAME_RECOMMEND_BANGUMI_TITLE = "recommend_bangumi_title";
    const ES_QUERY_FIELD_NAME_RECOMMEND_BANGUMI_TYPES_LIST = "recommend_bangumi_tags_list";
    const ES_QUERY_FIELD_NAME_RECOMMEND_BANGUMI_TYPE_ID = "recommend_bangumi_tag_id";
    const ES_QUERY_FIELD_NAME_RECOMMEND_BANGUMI_TYPE_NAME = "recommend_bangumi_tag_name";

    const ES_QUERY_FIELD_NAME_VIEWS = "views";
    const ES_QUERY_FIELD_NAME_VERIFIED = "verified";
    const ES_QUERY_FIELD_NAME_VERIFIED_TEXT = "verified_text";
    const ES_QUERY_FIELD_NAME_URL_WEB = "url_web";
    const ES_QUERY_FIELD_NAME_URL_MOBILE = "url_mobile";
    const ES_QUERY_FIELD_NAME_URL_REAL = "url_real";


    const ES_QUERY_RESULT_FIELD_NAME_ID = "id";
    const ES_QUERY_RESULT_FIELD_NAME_TITLE = "title";
    const ES_QUERY_RESULT_FIELD_NAME_COVER = "cover";
    const ES_QUERY_RESULT_FIELD_NAME_COUNT_SIZE = "countSize";
    const ES_QUERY_RESULT_FIELD_NAME_UPDATE_TIME = "updateTime";
    const ES_QUERY_RESULT_FIELD_NAME_SUBSCRIBE_SIZE = "subscribeSize";
    const ES_QUERY_RESULT_FIELD_NAME_ALBUM_LIST = "albumList";
    const ES_QUERY_RESULT_FIELD_NAME_USER_NAME = "userName";
    const ES_QUERY_RESULT_FIELD_NAME_GROUP_NAME = "groupName";
    const ES_QUERY_RESULT_FIELD_NAME_VIEW_COUNT = "viewCount";
    const ES_QUERY_RESULT_FIELD_NAME_DANMU_SIZE = "danmuSize";
    const ES_QUERY_RESULT_FIELD_NAME_SORT = "sort";
    const ES_QUERY_RESULT_FIELD_NAME_CONTENT_LIST = "contentList";
    const ES_QUERY_RESULT_FIELD_NAME_USER_AVATAR = "user_avatar";
    const ES_QUERY_RESULT_FIELD_NAME_SIGNATURE = "signature";
    const ES_QUERY_RESULT_FIELD_NAME_CONTRIBUTION_COUNT = "contributionCount";
    const ES_QUERY_RESULT_FIELD_NAME_FANS_COUNT = "fansCount";
    const ES_QUERY_RESULT_FIELD_NAME_CONTENT_ID = "contentId";
        //12.15番剧
    const ES_QUERY_RESULT_FIELD_NAME_INTRO = "intro";
    const ES_QUERY_RESULT_FIELD_NAME_TYPE_ID = "typeId";
    const ES_QUERY_RESULT_FIELD_NAME_TYPE_NAME = "typeName";
    const ES_QUERY_RESULT_FIELD_NAME_LAST_UPDATE_TIME = "lastUpdateTime";
    const ES_QUERY_RESULT_FIELD_NAME_YEAR = "year";
    const ES_QUERY_RESULT_FIELD_NAME_STATUS = "status";
    const ES_QUERY_RESULT_FIELD_NAME_WEEK = "week";
    const ES_QUERY_RESULT_FIELD_NAME_NAME = "name";
    const ES_QUERY_RESULT_FIELD_NAME_TYPE_LIST = "typeList";
    const ES_QUERY_RESULT_FIELD_NAME_VIDEO_LIST = "videoList";
    const ES_QUERY_RESULT_FIELD_NAME_UPDATE = "update";
    const ES_QUERY_RESULT_FIELD_NAME_RECOMMEND_LIST = "recommendList";
    const ES_QUERY_RESULT_FIELD_NAME_VIEWS = "views";
    const ES_QUERY_RESULT_FIELD_NAME_SOURCE_ID = "sourceId";
    const ES_QUERY_RESULT_FIELD_NAME_SOURCE_TYPE = "sourceType";
    const ES_QUERY_RESULT_FIELD_NAME_URL_WEB = "urlWeb";
    const ES_QUERY_RESULT_FIELD_NAME_URL_MOBILE = "urlMobile";
    const ES_QUERY_RESULT_FIELD_NAME_URL_REAL = "urlReal";

        //12.20用户认证
    const ES_QUERY_RESULT_FIELD_NAME_VERIFIED = "verified";
    const ES_QUERY_RESULT_FIELD_NAME_VERIFIED_TEXT = "verifiedText";






    const FASTRANK_FIELD_SCORE = "score";
}