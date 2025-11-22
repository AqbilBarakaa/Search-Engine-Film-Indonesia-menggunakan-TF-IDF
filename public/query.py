import re, sys, pickle, json

if len(sys.argv) != 4:
    print("\n\nPenggunaan\n\tquery.py [index] [n] [query]..\n")
    sys.exit(1)

index_path, n, query = sys.argv[1], int(sys.argv[2]), sys.argv[3].strip()

with open(index_path, 'rb') as f:
    index = pickle.load(f)

list_doc = {}

if query == "":
    for term in index:
        for doc in index[term]:
            title = doc['judul']
            if title not in list_doc:
                list_doc[title] = doc
else:
    for q_word in query.lower().split():
        if q_word in index:
            for doc in index[q_word]:
                title = doc['judul']
                if title not in list_doc:
                    list_doc[title] = doc.copy()
                    list_doc[title]['score'] = 0
                
                list_doc[title]['score'] += doc['score']

results = list(list_doc.values())

if query == "":
    results.sort(key=lambda x: x.get('tahun', 0), reverse=True)
else:
    results.sort(key=lambda x: x.get('score', 0), reverse=True)

for doc in results[:n]:
    print(json.dumps(doc))