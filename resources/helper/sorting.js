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

                    return rowB[index].toString().localeCompare(rowA[index]);
                }

                return rowA[index].toString().localeCompare(rowB[index]);
            }
        )
    }

    return{
        sortRecords
    }
}
