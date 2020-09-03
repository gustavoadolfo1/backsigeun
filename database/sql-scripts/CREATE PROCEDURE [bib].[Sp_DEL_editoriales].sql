-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_DEL_editoriales]
	 @_iEditorialId INTEGER

AS
BEGIN
	SET NOCOUNT ON;

	DELETE FROM bib.editoriales WHERE iEditorialId=@_iEditorialId
	
	IF @@ROWCOUNT>0
		BEGIN
			SELECT 1 iResult
			RETURN 1
		END
ErrorCapturado:
	SELECT 0 iResult
	RETURN 0
END
GO
