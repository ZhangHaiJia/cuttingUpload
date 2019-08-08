**此插件适用分割上传**

**示例**

        namespace zhanghaijia;

        $file = $_FILES['file'];

        $file_chunk = $_POST['chunk'];
        
        $file_chunks = $_POST['chunks'];
        
        $file_name = $_POST['name'];
        
        $cuttingUpload = new \CuttingUpload($file['tmp_name'], $file_chunk, $file_chunks, $file_name);
       
        $return_data = $cuttingUpload->apiReturn();
        
        if ($return_data['code'] == 2) {
        
                    $file = $return_data['file_path'];
                    
                    unset($return_data);
                    
                    $return_data = $this->cuttingImport($import_model, $type, $file);
               
                }

**CuttingUpload参数说明**

    $file['tmp_name'] :文件原来的临时位置

    $file_chunk : 第几个模块进入
 
    $file_chunks：总的模块数

    $file_name： 文件的名称

**返回说明**

    code:2 合并文件完成

    code:1 正常合并文件

**说明案例**
    code:2  合并文件完成后，返回了文件的路径，依据整个路径取得文件，接着进行文件的操作。