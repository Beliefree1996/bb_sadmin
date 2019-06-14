//判断元素是否在数组中
export function isInArray(item ,arr) {
    if(arr.length>0){
        for(let a of arr){
            if (item == a){
                return true
            }
        }
    }
    return false
}