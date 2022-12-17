export default function useSorting()
{
    let sortDirection = null;
    let sortIndex = null;

    const sortRecords  = (rows, rawRows, index) => {
        if (sortIndex === index) {
            switch (sortDirection) {
                case null:
                    sortDirection = 'asc';
                    break;
                case 'asc':
                    sortDirection = 'desc';
                    break;
                case 'desc':
                    sortDirection = 'asc';
                    break;
            }
        } else {
            sortDirection = 'asc'
        }
        sortIndex = index;

        rows.value = rawRows.value.sort(
            (rowA, rowB) => {
                if (sortDirection === 'desc') {
                  //  console.log(rowB[index]);
                    return rowB[index].localeCompare(rowA[index]);
                }
               // console.log(rowB[index]);
                return rowA[index].localeCompare(rowB[index]);
            }
        )
    }

    return{
        sortRecords
    }
}
