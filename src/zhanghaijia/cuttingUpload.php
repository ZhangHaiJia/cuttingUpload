<?php
/**
 * Created by PhpStorm.
 * User: zhangHaijia
 * Date: 8/8/2019
 * Time: 下午5:44
 */

class CuttingUpload
{
    private $file_path; //上传目录
    private $tmp_path;  //PHP文件临时目录
    private $blob_num; //第几个文件块
    private $total_blob_num; //文件块总数
    private $file_name; //文件名
    private $file_name_new; //文件名
    private $file_path_new; //文件名

    public function __construct($tmp_path, $blob_num, $total_blob_num, $file_name, $file_name_new = '', $file_path_new = '')
    {

        $this->tmp_path = $tmp_path;
        $this->blob_num = $blob_num + 1;
        $this->total_blob_num = $total_blob_num;
        $this->file_name = $file_name;
        $this->file_name_new = time() . rand(10000, 99999) . '.xlsx';
        $this->file_path = ROOT_PATH . 'public/uploads';
        $this->file_path_new = $this->file_path . '/' . date('Ymd', time()) . '/';
        $this->moveFile();
        $this->fileMerge();
    }


    //移动文件
    private function moveFile()
    {
        $this->touchDir();
        //新的地址
        $this->file_name = md5($this->file_name);
        $filename = $this->file_path . '/' . $this->file_name . '__' . $this->blob_num;
        if (move_uploaded_file($this->tmp_path, $filename)) {
            return true;
        } else {
            return false;
        }

    }


    //建立上传文件夹
    private function touchDir()
    {
        if (!file_exists($this->file_path)) {
            return mkdir($this->file_path, 755);
        }
        if (!file_exists($this->file_path_new)) {
            return mkdir($this->file_path_new, 755);
        }

    }


    //判断是否是最后一块，如果是则进行文件合成并且删除文件块
    private function fileMerge()
    {
        if ($this->blob_num == $this->total_blob_num) {
            $blob = '';
            for ($i = 1; $i <= $this->total_blob_num; $i++) {
                $blob .= file_get_contents($this->file_path . '/' . $this->file_name . '__' . $i);
            }

            file_put_contents($this->file_path_new . $this->file_name_new, $blob);
            $this->deleteFileBlob();
        }
    }


    //删除文件块
    private function deleteFileBlob()
    {
        for ($i = 1; $i <= $this->total_blob_num; $i++) {
            @unlink($this->file_path . '/' . $this->file_name . '__' . $i);
        }
    }

    //API返回数据
    public function apiReturn()
    {
        $data = [];
        if ($this->blob_num == $this->total_blob_num) {
            if (file_exists($this->file_path_new . $this->file_name_new)) {
                $data['code'] = 2;
                $data['msg'] = 'success';
                $data['file_path'] = $this->file_path_new . $this->file_name_new;
            }
        } else {
            if (file_exists($this->file_path . '/' . $this->file_name . '__' . $this->blob_num)) {
                $data['code'] = 1;
                $data['msg'] = 'waiting for all';
                $data['file_path'] = '';
            }
        }

        return $data;
    }

}