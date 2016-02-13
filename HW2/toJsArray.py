def toJSArray(textFile):

    try:
        L = []

        with open(textFile, "r") as fileHandle:
            for line in fileHandle:
                #L.append(fileHandle.readline())
                L.append(line.strip('\n'))

        with open(textFile, "w+") as fileHandle:
            fileHandle.write("var emailDomains = [\n")

            for item in L:
                fileHandle.write("\"" + item + "\",\n")

            fileHandle.write("]")

    except:
        print("FAIL")
        pass

if __name__ == '__main__':
    toJSArray("emailDomains.txt")
