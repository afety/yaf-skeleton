<?php

/**
 * @return string client library version
 */
function fastdfs_client_version(): string
{
}

/**
 * @return int last error no
 */
function fastdfs_get_last_error_no(): int
{
}

/**
 * @return string last error info
 */
function fastdfs_get_last_error_info(): string
{
}

/**
 * generate anti-steal token for HTTP download
 * @param string $remote_filename the remote filename (do NOT including group name)
 * @param int $timestamp the timestamp (unix timestamp)
 * @return string token string for success, false for error
 */
function fastdfs_http_gen_token(string $remote_filename, int $timestamp): string
{
}

/**
 * get file info from the filename
 * @param string $group_name the group name of the file
 * @param string $filename the filename on the storage server
 * @return array assoc array for success, false for error.
 *          the assoc array including following elements:
 *          create_timestamp: the file create timestamp (unix timestamp)
 *          file_size: the file size (bytes)
 *          source_ip_addr: the source storage server ip address
 */
function fastdfs_get_file_info(string $group_name, string $filename): array
{
}

/**
 * get file info from the file id
 * @param string $file_id the file id (including group name and filename) or remote filename
 * @return array assoc array for success, false for error.
 * the assoc array including following elements:
 * create_timestamp: the file create timestamp (unix timestamp)
 * file_size: the file size (bytes)
 * source_ip_addr: the source storage server ip address
 */
function fastdfs_get_file_info1(string $file_id): array
{
}

/**
 * @param int $sock the unix socket description
 * @param string $buff the buff to send
 * @return bool true for success, false for error
 */
function fastdfs_send_data(int $sock, string $buff): bool
{
}

/**
 * generate slave filename by master filename, prefix name and file extension name
 * @param string $master_filename the master filename / file id to generate the slave filename
 * @param string $prefix_name the prefix name  to generate the slave filename
 * @param string $file_ext_name slave file extension name, can be null or emtpy
 * @return string slave filename string for success, false for error
 */
function fastdfs_gen_slave_filename(string $master_filename, string $prefix_name, string $file_ext_name = ''): string
{
}

/**
 * check file exist
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements: ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements: ip_addr, port and sock
 * @return bool true for exist, false for not exist
 */
function fastdfs_storage_file_exist(string $group_name, string $remote_filename, array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * @param string $file_id the file id of the file
 * @param array $tracker_server the tracker server assoc array including elements: ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements: ip_addr, port and sock
 * @return bool true for exist, false for not exist
 */
function fastdfs_storage_file_exist1(string $file_id, array $tracker_server = [], array $storage_server = []): bool
{
}


/**
 * upload local file to storage server
 * @param string $local_filename the local filename
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements: ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements: ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_by_filename(string $local_filename
    , string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                            array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload local file to storage server
 * @param string $local_filename the local filename
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements: ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements: ip_addr, port and sock
 * @return string file_id for success, false for error.
 */
function fastdfs_storage_upload_by_filename1(string $local_filename
    , string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                             array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file buff to storage server
 * @param string $file_buff the file content
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements: ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements: ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_by_filebuff(string $file_buff
    , string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                            array $tracker_server = [], array $storage_server = []): array
{
}


/**
 * upload file buff to storage server
 * @param string $file_buff the file content
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error
 */
function fastdfs_storage_upload_by_filebuff1(string $file_buff,
                                             string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                             array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file to storage server by callback
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_by_callback(array $callback_array,
                                            string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                            array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload file to storage server by callback
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array file_id for success, false for error
 */
function fastdfs_storage_upload_by_callback1(array $callback_array
    , string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                             array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * @param string $local_filename the local filename
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_appender_by_filename(string $local_filename,
                                                     string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                     array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload local file to storage server as appender file
 * @param string $local_filename the local filename
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error.
 */
function fastdfs_storage_upload_appender_by_filename1(string $local_filename,
                                                      string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                      array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file buff to storage server as appender file
 * @param string $file_buff the file content
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_appender_by_filebuff(string $file_buff,
                                                     string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                     array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload file buff to storage server as appender file
 * @param string $file_buff the file content
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error
 */
function fastdfs_storage_upload_appender_by_filebuff1(string $file_buff,
                                                      string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                      array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file to storage server by callback as appender file
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_appender_by_callback(array $callback_array,
                                                     string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                     array $tracker_server = [], array $storage_server = []): array
{
}


/**
 * upload file to storage server by callback as appender file
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $group_name specify the group name to store the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error
 */
function fastdfs_storage_upload_appender_by_callback1(array $callback_array,
                                                      string $file_ext_name = '', array $meta_list = [], string $group_name = '',
                                                      array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * append local file to the appender file of storage server
 * @param string $local_filename the local filename
 * @param string $group_name the the group name of appender file
 * @param $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_append_by_filename(string $local_filename,
                                            string $group_name, $appender_filename,
                                            array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * append local file to the appender file of storage server
 * @param string $local_filename the local filename
 * @param string $appender_file_id the appender file id
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string true for success, false for error
 */
function fastdfs_storage_append_by_filename1(string $local_filename,
                                             string $appender_file_id, array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * append file buff to the appender file of storage server
 * @param string $file_buff the file content
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool  true for success, false for error
 */
function fastdfs_storage_append_by_filebuff(string $file_buff,
                                            string $group_name, string $appender_filename,
                                            array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * append file buff to the appender file of storage server
 * @param string $file_buff the file content
 * @param string $appender_file_id the appender file id
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_append_by_filebuff1(string $file_buff,
                                             string $appender_file_id, array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * append file to the appender file of storage server by callback
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_append_by_callback(array $callback_array,
                                            string $group_name, string $appender_filename,
                                            array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * append file buff to the appender file of storage server
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $appender_file_id the appender file id
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_append_by_callback1(array $callback_array,
                                             string $appender_file_id, array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by local file
 * @param string $local_filename the local filename
 * @param int $file_offset offset of appender file
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_filename(string $local_filename,
                                            int $file_offset, string $group_name, string $appender_filename,
                                            array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by local file
 * @param string $local_filename the local filename
 * @param long $file_offset offset of appender file
 * @param string $appender_file_id the appender file id
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_filename1(string $local_filename,
                                             long $file_offset, string $appender_file_id
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by file buff
 * @param string $file_buff the file content
 * @param int $file_offset offset of appender file
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_filebuff(string $file_buff,
                                            int $file_offset, string $group_name, string $appender_filename
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by file buff
 * @param string $file_buff the file content
 * @param int $file_offset offset of appender file
 * @param string $appender_file_id the appender file id
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_filebuff1(string $file_buff,
                                             int $file_offset, string $appender_file_id
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by callback
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param int $file_offset offset of appender file
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_callback(array $callback_array,
                                            int $file_offset, string $group_name, string $appender_filename
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * modify appender file by callback
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param int $file_offset offset of appender file
 * @param string $group_name the appender file id
 * @param string $appender_filename
 * @param array $tracker_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_modify_by_callback1(array $callback_array,
                                             int $file_offset, string $group_name, string $appender_filename
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * truncate appender file to specify size
 * @param string $group_name the the group name of appender file
 * @param string $appender_filename the appender filename
 * @param int $truncated_file_size truncate the file size to
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_truncate_file(string $group_name,
                                       string $appender_filename, int $truncated_file_size = 0,
                                       array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * truncate appender file to specify size
 * @param string $appender_file_id the appender file id
 * @param int $truncated_file_size truncate the file size to
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_truncate_file1(string $appender_file_id
    , int $truncated_file_size = 0, array $tracker_server = [],
                                        array $storage_server = []): bool
{
}

/**
 * upload local file to storage server (slave file mode)
 * @param string $local_filename the file content
 * @param string $group_name the group name of the master file
 * @param string $master_filename the master filename to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return mixed assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_slave_by_filename(string $local_filename,
                                                  string $group_name, string $master_filename, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                  array $tracker_server = [], array $storage_server = [])
{
}

/**
 * upload local file to storage server (slave file mode)
 * @param string $local_filename the local filename
 * @param string $master_file_id the master file id to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error.
 */
function fastdfs_storage_upload_slave_by_filename1(string $local_filename,
                                                   string $master_file_id, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                   array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file buff to storage server (slave file mode)
 * @param string $file_buff the file content
 * @param string $group_name the group name of the master file
 * @param string $master_filename the master filename to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_slave_by_filebuff(string $file_buff,
                                                  string $group_name, string $master_filename, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                  array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload file buff to storage server (slave file mode)
 * @param string $file_buff the file content
 * @param string $master_file_id the master file id to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error
 */
function fastdfs_storage_upload_slave_by_filebuff1(string $file_buff,
                                                   string $master_file_id, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                   array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * upload file to storage server by callback (slave file mode)
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $group_name the group name of the master file
 * @param string $master_filename the master filename to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error.
 * the returned array includes elements: group_name and filename
 */
function fastdfs_storage_upload_slave_by_callback(array $callback_array,
                                                  string $group_name, string $master_filename, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                  array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * upload file to storage server by callback (slave file mode)
 * @param array $callback_array the callback assoc array, must have keys:
 * callback  - the php callback function name
 * callback function prototype as:
 * function upload_file_callback($sock, $args)
 * file_size - the file size
 * args      - use argument for callback function
 * @param string $master_file_id the master file id to generate the slave file id
 * @param string $prefix_name the prefix name to generage the slave file id
 * @param string $file_ext_name the file extension name, do not include dot(.)
 * @param array $meta_list meta data assoc array, such as
 * array('width'=>1024, 'height'=>768)
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string file_id for success, false for error
 */
function fastdfs_storage_upload_slave_by_callback1(array $callback_array,
                                                   string $master_file_id, string $prefix_name
    , string $file_ext_name = '', array $meta_list = [],
                                                   array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * delete file from storage server
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_delete_file(string $group_name, string $remote_filename
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * delete file from storage server
 * @param string $file_id the file id to be deleted
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_delete_file1(string $file_id
    , array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * get file content from storage server
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string the file content for success, false for error
 */
function fastdfs_storage_download_file_to_buff(string $group_name,
                                               string $remote_filename, int $file_offset = 0, int $download_bytes = 0,
                                               array $tracker_server = [], array $storage_server = []): string
{
}


/**
 * get file content from storage server
 * @param string $file_id the file id of the file
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return string the file content for success, false for error
 */
function fastdfs_storage_download_file_to_buff1(string $file_id
    , int $file_offset = 0, int $download_bytes = 0,
                                                array $tracker_server = [], array $storage_server = []): string
{
}

/**
 * download file from storage server to local file
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param string $local_filename the local filename to save the file content
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_download_file_to_file(string $group_name,
                                               string $remote_filename, string $local_filename, int $file_offset = 0,
                                               int $download_bytes = 0, array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * download file from storage server to local file
 * @param string $file_id the file id of the file
 * @param string $local_filename the local filename to save the file content
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_download_file_to_file1(string $file_id,
                                                string $local_filename, int $file_offset = 0, int $download_bytes = 0,
                                                array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $download_callback the download callback array, elements as:
 * callback  - the php callback function name
 * callback function prototype as:
 * function my_download_file_callback($args, $file_size, $data)
 * args      - use argument for callback function
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_download_file_to_callback(string $group_name,
                                                   string $remote_filename, array $download_callback, int $file_offset = 0,
                                                   int $download_bytes = 0, array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * @param string $file_id the file id of the file
 * @param array $download_callback the download callback array, elements as:
 * callback  - the php callback function name
 * callback function prototype as:
 * function my_download_file_callback($args, $file_size, $data)
 * args      - use argument for callback function
 * @param int $file_offset file start offset, default value is 0
 * @param int $download_bytes 0 (default value) means from the file offset to
 * the file end
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_download_file_to_callback1(string $file_id,
                                                    array $download_callback, int $file_offset = 0, int $download_bytes = 0,
                                                    array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * set meta data of the file
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $meta_list meta data assoc array to be set, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $op_type operate flag, can be one of following flags:
 * FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
 * FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_set_metadata(string $group_name, string $remote_filename,
                                      array $meta_list, string $op_type = '', array $tracker_server = [],
                                      array $storage_server = []): bool
{
}

/**
 * set meta data of the file
 * @param string $file_id the file id of the file
 * @param array $meta_list meta data assoc array to be set, such as
 * array('width'=>1024, 'height'=>768)
 * @param string $op_type operate flag, can be one of following flags:
 * FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
 * FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_storage_set_metadata1(string $file_id, array $meta_list
    , string $op_type = '', array $tracker_server = [], array $storage_server = []): bool
{
}

/**
 * get meta data of the file
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * returned array like: array('width' => 1024, 'height' => 768)
 */
function fastdfs_storage_get_metadata(string $group_name, string $remote_filename
    , array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * get meta data of the file
 * @param string $file_id the file id of the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @param array $storage_server the storage server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * returned array like: array('width' => 1024, 'height' => 768)
 */
function fastdfs_storage_get_metadata1(string $file_id
    , array $tracker_server = [], array $storage_server = []): array
{
}

/**
 * connect to the server
 * @param string $ip_addr the ip address of the server
 * @param int $port the port of the server
 * @return array assoc array for success, false for error
 */
function fastdfs_connect_server(string $ip_addr, int $port): array
{
}

/**
 * disconnect from the server
 * @param array $server_info the assoc array including elements:
 * ip_addr, port and sock
 * @return bool true for success, false for error
 */
function fastdfs_disconnect_server(array $server_info): bool
{
}

/**
 * send ACTIVE_TEST cmd to the server
 * @param array $server_info the assoc array including elements:
 * ip_addr, port and sock, sock must be connected
 * @return bool true for success, false for error
 */
function fastdfs_active_test(array $server_info): bool
{
}

/**
 * get a connected tracker server
 * @return array assoc array for success, false for error
 * the assoc array including elements: ip_addr, port and sock
 */
function fastdfs_tracker_get_connection(): array
{
}

/**
 * connect to all tracker servers
 * @return bool true for success, false for error
 */
function fastdfs_tracker_make_all_connections(): bool
{
}

/**
 * close all connections to the tracker servers
 * @return  true for success, false for error
 */
function fastdfs_tracker_close_all_connections(): bool
{
}

/**
 * get group stat info
 * @param string $group_name specify the group name, null or empty string means all groups
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array index array for success, false for error, each group as a array element
 */
function fastdfs_tracker_list_groups(string $group_name = '', array $tracker_server = []): array
{
}

/**
 * get the storage server info to upload file
 * @param string $group_name specify the group name
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error. the assoc array including
 * elements: ip_addr, port, sock and store_path_index
 */
function fastdfs_tracker_query_storage_store(string $group_name = '',
                                             array $tracker_server = []): array
{
}

/**
 * get the storage server list to upload file
 * @param string $group_name specify the group name
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array indexed storage server array for success, false for error.
 * each element is an assoc array including elements:
 * ip_addr, port, sock and store_path_index
 */
function fastdfs_tracker_query_storage_store_list(string $group_name = '',
                                                  array $tracker_server = []): array
{
}

/**
 * get the storage server info to set metadata
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * the assoc array including elements: ip_addr, port and sock
 */
function fastdfs_tracker_query_storage_update(string $group_name,
                                              string $remote_filename, array $tracker_server = []): array
{
}

/**
 * get the storage server info to set metadata
 * @param string $file_id the file id of the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * the assoc array including elements: ip_addr, port and sock
 */
function fastdfs_tracker_query_storage_update1(string $file_id,
                                               array $tracker_server = []): array
{
}

/**
 * get the storage server info to download file (or get metadata)
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * the assoc array including elements: ip_addr, port and sock
 */
function fastdfs_tracker_query_storage_fetch(string $group_name,
                                             string $remote_filename, array $tracker_server = []): array
{
}

/**
 * get the storage server info to download file (or get metadata)
 * @param string $file_id the file id of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array assoc array for success, false for error
 * the assoc array including elements: ip_addr, port and sock
 */
function fastdfs_tracker_query_storage_fetch1(string $file_id
    , string $remote_filename, array $tracker_server = []): array
{
}

/**
 * get the storage server list which can retrieve the file content or metadata
 * @param string $group_name the group name of the file
 * @param string $remote_filename the filename on the storage server
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array index array for success, false for error.
 * each server as an array element
 */
function fastdfs_tracker_query_storage_list(string $group_name,
                                            string $remote_filename, array $tracker_server = []): array
{
}

/**
 * @param string $file_id get the storage server list which can retrieve the file content or metadata
 * @param string $remote_filename the file id of the file
 * @param array $tracker_server the tracker server assoc array including elements:
 * ip_addr, port and sock
 * @return array index array for success, false for error.
 * each server as an array element
 */
function fastdfs_tracker_query_storage_list1(string $file_id
    , string $remote_filename, array $tracker_server = []): array
{
}

/**
 * delete the storage server from the cluster
 * @param string $group_name the group name of the storage server
 * @param string $storage_ip the ip address of the storage server to be deleted
 * @return bool true for success, false for error
 */
function fastdfs_tracker_delete_storage(string $group_name, string $storage_ip): bool
{
}